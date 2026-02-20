<?php

namespace App\Domain\Firebase\UseCase;

use App\Components\CommonComponent;
use App\Domain\Firebase\Entity\FirebaseNotificationEntity;
use App\Domain\Firebase\Repository\FirebaseRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetFirebaseNotificationsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-firebase-notifications';
    public function __construct(
        private FirebaseRepository $firebaseRepository
    ) {
    }

    public function __invoke(FirebaseNotificationEntity $entity): FirebaseNotificationEntity
    {
        $this->authorize();

        $data = $this->firebaseRepository->getNotifications($entity);

        $entity->setNotifications(
            collect($data->items())->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'content' => $notification->content,
                    'push_type' => $notification->push_type,
                    'push_datetime' => $notification->push_datetime?->format('Y/m/d H:i:s'),
                    'push_now_flag' => $notification->push_now_flag,
                    'created_at' => $notification->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $notification->updated_at->format('Y/m/d H:i:s'),
                    'read_at' => $notification->read_at?->format('Y/m/d H:i:s'),
                    'notification_unread_count' => $notification->notification_unread_count,
                ];
            })->toArray()
        );

        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatusCode(StatusCode::OK);

        return $entity;
    }
}
