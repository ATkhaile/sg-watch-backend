<?php

namespace App\Domain\Notifications\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\Notifications\Entity\CreateNotificationsRequestEntity;
use App\Domain\Notifications\Entity\UpdateNotificationsRequestEntity;
use App\Domain\Notifications\Entity\NotificationDetailEntity;
use App\Domain\Notifications\Entity\NotificationEntity;
use App\Domain\Notifications\Repository\NotificationsRepository;
use App\Enums\PushType;
use App\Enums\StatusCode;
use App\Models\Notification;
use App\Services\NotificationPusherService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Enums\StorageFolder;

class DbNotificationsInfrastructure implements NotificationsRepository
{
    private Notification $notification;
    private NotificationPusherService $notificationPusher;
    public function __construct(Notification $notification, NotificationPusherService $notificationPusher)
    {
        $this->notification = $notification;
        $this->notificationPusher = $notificationPusher;
    }

    public function getAll(NotificationEntity $entity): LengthAwarePaginator
    {
        $query = $this->notification->join('users', 'notifications.create_user_id', '=', 'users.id')
            ->select(
                'notifications.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as create_user_name")
            )
            ->whereNull('notifications.deleted_at');

        if ($searchKey = $entity->getSearch()) {
            $query->where(function ($q) use ($searchKey) {
                $q->where(CommonComponent::escapeLikeSentence('title', $searchKey))
                    ->orWhere(CommonComponent::escapeLikeSentence('content', $searchKey));
            });
        }

        if ($searchType = $entity->getType()) {
            $query->where('push_type', $searchType);
        }

        if ($searchSenderType = $entity->getSenderType()) {
            $query->where('sender_type', $searchSenderType);
        }

        $sort = $entity->getSort();
        $direction = $entity->getDirection();

        $query->orderBy($sort, $direction);


        return $query->paginate(
            $entity->getLimit(),
            ['*'],
            'page',
            $entity->getPage()
        );
    }

    public function getDetail(int $notificationId): ?NotificationDetailEntity
    {
        $notification = $this->notification->join('users', 'notifications.create_user_id', '=', 'users.id')
            ->select(
                'notifications.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as create_user_name")
            )
            ->where('notifications.id', $notificationId)
            ->first();
        if (!$notification) {
            return null;
        }

        return new NotificationDetailEntity(
            id: $notification->id,
            title: $notification->title,
            content: $notification->content,
            push_type: $notification->push_type,
            sender_type: $notification->sender_type,
            push_datetime: $notification->push_datetime?->format('Y/m/d H:i:s'),
            push_now_flag: $notification->push_now_flag,
            created_at: $notification->created_at->format('Y/m/d H:i:s'),
            updated_at: $notification->updated_at->format('Y/m/d H:i:s'),
            create_user_name: $notification->create_user_name,
            image_url: $notification->image_url,
            statusCode: StatusCode::OK
        );
    }

    public function store(CreateNotificationsRequestEntity $entity): bool
    {
        try {
            $userId = auth()->user()->id;
            DB::beginTransaction();

            $imageFile = null;
            if ($entity->file && $entity->push_type == PushType::FIREBASE) {
                $extension = $entity->file->getClientOriginalExtension();
                $imageFile = CommonComponent::uploadFileName($extension);

                CommonComponent::uploadFile(
                    StorageFolder::Image . '/' . $userId,
                    $entity->file,
                    $imageFile
                );
            }

            $notification = new Notification;
            $notification->title = $entity->title;
            $notification->content = $entity->content;
            $notification->push_type = $entity->push_type;
            $notification->push_datetime = $entity->push_datetime;
            $notification->push_now_flag = $entity->push_now_flag;
            $notification->image_file = $imageFile;
            $notification->create_user_id = $userId;
            $notification->sender_type = $entity->sender_type;
            if (!$notification->save()) {
                return false;
            }

            if (!$notification->push_now_flag) {
                DB::commit();
                return true;
            }

            $this->notificationPusher->pushNotification($notification->id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update(UpdateNotificationsRequestEntity $entity, int $id): bool
    {
        try {
            $userId = auth()->user()->id;
            DB::beginTransaction();

            $notification = $this->notification->find($id);
            if (!$notification) {
                return false;
            }

            $pushFlag = false;
            if ($entity->getPushNowFlag() === true && !$notification->push_now_flag) {
                $pushFlag = true;
            }

            if ($entity->getTitle() !== null) {
                $notification->title = $entity->getTitle();
            }
            if ($entity->getContent() !== null) {
                $notification->content = $entity->getContent();
            }
            if ($entity->getPushType() !== null) {
                $notification->push_type = $entity->getPushType();
            }
            if ($entity->getSenderType() !== null) {
                $notification->sender_type = $entity->getSenderType();
            }
            if ($entity->getPushDatetime() !== null) {
                $notification->push_datetime = $entity->getPushDatetime();
            }
            if ($entity->getPushNowFlag() !== null) {
                $notification->push_now_flag = $entity->getPushNowFlag();
            }
            if ($entity->getFile() && $notification->push_type == PushType::FIREBASE) {
                $extension = $entity->getFile()->getClientOriginalExtension();
                $fileName = CommonComponent::uploadFileName($extension);

                $uploadResult = CommonComponent::uploadFile(
                    StorageFolder::Image . '/' . $userId,
                    $entity->getFile(),
                    $fileName
                );

                if ($uploadResult) {
                    $notification->image_file = $fileName;
                }
            }

            if (!$notification->save()) {
                return false;
            }

            if ($pushFlag) {
                $this->notificationPusher->pushNotification($notification->id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete(int $notificationId): bool
    {
        $notification = $this->notification->find($notificationId);
        if (!$notification) {
            return false;
        }

        if ($notification->image_file) {
            CommonComponent::deleteFile(StorageFolder::Image . '/' . auth()->user()->id, $notification->image_file);
        }

        return $notification->delete();
    }
}
