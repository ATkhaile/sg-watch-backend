<?php

namespace App\Domain\NotificationPush\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\NotificationPush\Entity\CreateNotificationPushRequestEntity;
use App\Domain\NotificationPush\Entity\NotificationPushDetailEntity;
use App\Domain\NotificationPush\Entity\NotificationPushEntity;
use App\Domain\NotificationPush\Entity\NotificationPushHistoryEntity;
use App\Domain\NotificationPush\Entity\UpdateNotificationPushRequestEntity;
use App\Domain\NotificationPush\Entity\UpdateReceiveNotificationSettingRequestEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Enums\IOSSystemSound;
use App\Enums\NotificationPushProcess;
use App\Enums\RedirectType;
use App\Enums\StatusCode;
use App\Enums\StorageFolder;
use App\Models\NotificationPush;
use App\Models\UserNotificationHistory;
use App\Models\UserNotificationPush;
use App\Services\NotificationPusherService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Jobs\MessagePush;
use App\Models\FcmToken;

class DbNotificationPushInfrastructure implements NotificationPushRepository
{
    public function __construct(
        private NotificationPush $notificationPush,
        private UserNotificationPush $userNotificationPush,
        private NotificationPusherService $notificationPusherService,
        private UserNotificationHistory $userNotificationHistory,
        private FcmToken $fcmToken
    ) {}

    public function getAll(NotificationPushEntity $entity): LengthAwarePaginator
    {
        $q = $this->notificationPush
            ->newQuery()
            ->select([
                'id',
                'title',
                'message',
                'img_path',
                'all_user_flag',
                'push_now_flag',
                'push_schedule',
                'created_by',
                'created_at',
                'updated_at',
            ]);

        if (($search = $entity->search) !== null && $search !== '') {
            $q->where(function ($sub) use ($search) {
                $sub->where(CommonComponent::escapeLikeSentence('title', $search))
                    ->orWhere(CommonComponent::escapeLikeSentence('message', $search));
            });
        }

        $direction = $entity->direction ?? 'DESC';
        $sort = $entity->sort ?? 'updated_at';

        $q->orderBy($sort, $direction);

        return $q->paginate(
            $entity->limit ?? 10,
            ['*'],
            'page',
            $entity->page ?? 1
        );
    }

    public function store(CreateNotificationPushRequestEntity $entity, int $currentUserId): bool
    {
        return DB::transaction(function () use ($entity, $currentUserId) {
            $imgPath = null;
            if ($entity->img_path && $entity->img_path->isValid()) {
                $extension  = $entity->img_path->getClientOriginalExtension();
                $fileName   = CommonComponent::uploadFileName($extension);
                $uploadPath = CommonComponent::uploadFile(
                    StorageFolder::NOTIFICATION_PUSH,
                    $entity->img_path,
                    $fileName
                );

                if ($uploadPath !== false) {
                    $imgPath = $fileName;
                }
            }
            $attachFileName = null;
            if (
                in_array($entity->redirect_type,  [RedirectType::IMAGE, RedirectType::VIDEO], true)
                && $entity->attach_file
                && $entity->attach_file->isValid()
            ) {
                $extension  = $entity->attach_file->getClientOriginalExtension();
                $fileName   = CommonComponent::uploadFileName($extension);
                $uploadPath = CommonComponent::uploadFile(
                    StorageFolder::NOTIFICATION_PUSH,
                    $entity->attach_file,
                    $fileName
                );

                if ($uploadPath !== false) {
                    $attachFileName = $fileName;
                }
            }

            $push = new NotificationPush();
            $push->title = $entity->title;
            $push->message = $entity->message;
            $push->img_path = $imgPath;
            $push->all_user_flag  = $entity->all_user_flag;
            $push->push_now_flag  = $entity->push_now_flag;
            $push->push_schedule  = $entity->push_schedule;
            $sound = $entity->sound;
            if ($sound === null || $sound === '') {
                $sound = IOSSystemSound::DEFAULT;
            }
            $push->sound = $sound;
            $push->redirect_type = $entity->redirect_type;
            $push->app_page_id = null;
            $push->attach_file = null;
            $push->attach_link = null;

            if ($entity->redirect_type === RedirectType::APP_PAGE) {
                $push->app_page_id = $entity->app_page_id;
            } elseif (in_array($entity->redirect_type, [RedirectType::IMAGE, RedirectType::VIDEO], true)) {
                $push->attach_file = $attachFileName;
            } elseif ($entity->redirect_type === RedirectType::WEBVIEW) {
                $push->attach_link = $entity->attach_link;
            }
            $push->created_by = $currentUserId;
            $push->process = $entity->push_now_flag
                ? NotificationPushProcess::SUCCESSFULLY
                : NotificationPushProcess::WAITING;

            if (!$push->save()) {
                return false;
            }

            if (!$entity->all_user_flag && !empty($entity->user_ids)) {
                $rows = [];
                foreach ($entity->user_ids as $userId) {
                    $rows[] = [
                        'notification_push_id' => $push->id,
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at'  => now(),
                    ];
                }
                $this->userNotificationPush->insert($rows);
            }

            if ($entity->push_now_flag) {
                MessagePush::dispatch($push->id);
                // $this->notificationPusherService->pushMessage($push->id);
            }

            return true;
        });
    }

    public function getDetail(int $id): ?NotificationPushDetailEntity
    {
        $push = $this->notificationPush->with('users:id')->find($id);

        if (!$push) {
            return null;
        }

        $data = $push->toArray();
        unset($data['users']);
        $data['user_ids'] = $push->users->pluck('id')->all();

        return new NotificationPushDetailEntity(
            notification_push: $data,
            status_code: StatusCode::OK
        );
    }

    public function update(UpdateNotificationPushRequestEntity $entity, int $id, int $currentUserId): bool
    {
        return DB::transaction(function () use ($entity, $id, $currentUserId) {
            $push = $this->notificationPush->find($id);
            if (!$push) {
                return false;
            }
            $originalProcess = $push->process;

            $imgPath = $push->img_path;

            if ($entity->remove_image) {
                $imgPath = null;
            } elseif ($entity->img_path && $entity->img_path->isValid()) {
                $extension  = $entity->img_path->getClientOriginalExtension();
                $fileName   = CommonComponent::uploadFileName($extension);
                $uploadPath = CommonComponent::uploadFile(
                    StorageFolder::NOTIFICATION_PUSH,
                    $entity->img_path,
                    $fileName
                );

                if ($uploadPath !== false) {
                    $imgPath = $fileName;
                }
            }
            $attachFile = $push->attach_file;

            if ($entity->remove_attach_file || !in_array($entity->redirect_type, [RedirectType::IMAGE, RedirectType::VIDEO], true)) {
                $attachFile = null;
            } elseif ($entity->attach_file && $entity->attach_file->isValid()) {
                $extension  = $entity->attach_file->getClientOriginalExtension();
                $fileName   = CommonComponent::uploadFileName($extension);
                $uploadPath = CommonComponent::uploadFile(
                    StorageFolder::NOTIFICATION_PUSH,
                    $entity->attach_file,
                    $fileName
                );

                if ($uploadPath !== false) {
                    $attachFile = $fileName;
                }
            }
            $push->title = $entity->title;
            $push->message  = $entity->message;
            $push->img_path = $imgPath;
            $push->all_user_flag  = $entity->all_user_flag;
            $push->push_now_flag  = $entity->push_now_flag;
            $push->push_schedule  = $entity->push_schedule;
            $sound = $entity->sound;
            if ($sound === null || $sound === '') {
                $sound = IOSSystemSound::DEFAULT;
            }
            $push->sound = $sound;
            $push->redirect_type = $entity->redirect_type;
            $push->app_page_id = null;
            $push->attach_file = null;
            $push->attach_link = null;

            if ($entity->redirect_type === RedirectType::APP_PAGE) {
                $push->app_page_id = $entity->app_page_id;
            } elseif (in_array($entity->redirect_type, [RedirectType::IMAGE, RedirectType::VIDEO], true)) {
                $push->attach_file = $attachFile;
            } elseif ($entity->redirect_type === RedirectType::WEBVIEW) {
                $push->attach_link = $entity->attach_link;
            }
            $push->created_by = $currentUserId;
            $push->process = $entity->push_now_flag
                ? NotificationPushProcess::SUCCESSFULLY
                : NotificationPushProcess::WAITING;

            if (!$push->save()) {
                return false;
            }

            $this->userNotificationPush
                ->where('notification_push_id', $push->id)
                ->delete();

            if (!$entity->all_user_flag && !empty($entity->user_ids)) {
                $rows = [];
                foreach ($entity->user_ids as $userId) {
                    $rows[] = [
                        'notification_push_id' => $push->id,
                        'user_id'  => $userId,
                        'created_at'  => now(),
                        'updated_at' => now(),
                    ];
                }
                $this->userNotificationPush->insert($rows);
            }

            if ($entity->push_now_flag && $originalProcess === NotificationPushProcess::WAITING) {
                // $this->notificationPusherService->pushMessage($push->id);
                MessagePush::dispatch($push->id);
            }

            return true;
        });
    }

    public function delete(int $id): bool
    {
        $push = $this->notificationPush->find($id);
        if (!$push) {
            return false;
        }

        return (bool) $push->delete();
    }
    public function getAllNotificationPushHistory(NotificationPushHistoryEntity $entity, int $notificationPushId): LengthAwarePaginator
    {
        $query = $this->userNotificationHistory
            ->join('users', 'users.id', '=', 'user_notification_histories.user_id')
            ->join('fcm_tokens', 'fcm_tokens.id', '=', 'user_notification_histories.fcm_token_id')
            ->leftJoin('app_versions', 'app_versions.id', '=', 'fcm_tokens.app_version_id')
            ->where('user_notification_histories.notification_push_id', $notificationPushId)
            ->select([
                'user_notification_histories.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_name"),
                'fcm_tokens.device_name',
                'app_versions.version_name',
                'user_notification_histories.created_at',
            ]);

        return $query->paginate(
            $entity->limit ?? 10,
            ['*'],
            'page',
            $entity->page ?? 1
        );
    }
    public function updateReceiveNotificationSetting(UpdateReceiveNotificationSettingRequestEntity $entity,int $userId): bool {
        $token = $this->fcmToken
            ->newQuery()
            ->where('fcm_token', $entity->fcmToken)
            ->where('user_id', $userId)
            ->first();

        if (!$token) {
            return false;
        }

         if (!is_null($entity->receiveNotificationChat)) {
            $token->receive_notification_chat = $entity->receiveNotificationChat;
        }

        if (!is_null($entity->receiveReservation)) {
            $token->receive_reservation = $entity->receiveReservation;
        }

        if (is_null($entity->receiveNotificationChat) && is_null($entity->receiveReservation)) {
            return true;
        }
        return $token->save();
    }
}
