<?php

namespace App\Domain\Notifications\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Components\CommonComponent;
use App\Domain\Notifications\Entity\NotificationEntity;
use App\Domain\Notifications\Repository\NotificationsRepository;
use App\Enums\StatusCode;

final class GetAllNotificationsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-notifications';

    public function __construct(
        private NotificationsRepository $notificationsRepository
    ) {
    }

    public function __invoke(NotificationEntity $entity): NotificationEntity
    {
        $this->authorize();

        $data = $this->notificationsRepository->getAll($entity);

        $entity->setNotifications(
            collect($data->items())->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'content' => $notification->content,
                    'push_type' => $notification->push_type,
                    'sender_type' => $notification->sender_type,
                    'push_datetime' => $notification->push_datetime?->format('Y/m/d H:i:s'),
                    'push_now_flag' => $notification->push_now_flag,
                    'created_at' => $notification->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $notification->updated_at->format('Y/m/d H:i:s'),
                    'create_user_name' => $notification->create_user_name,
                    'image_url' => $notification->image_url,
                ];
            })->toArray()
        );

        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatusCode(StatusCode::OK);

        return $entity;
    }
}
