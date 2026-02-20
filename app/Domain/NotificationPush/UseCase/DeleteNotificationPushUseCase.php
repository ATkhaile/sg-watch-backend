<?php

namespace App\Domain\NotificationPush\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\NotificationPush\Entity\DeleteNotificationPushRequestEntity;
use App\Domain\NotificationPush\Entity\StatusEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Enums\StatusCode;

final class DeleteNotificationPushUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-notification-push';

    public function __construct(
        private NotificationPushRepository $notificationPushRepository
    ) {}

    public function __invoke(DeleteNotificationPushRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        if ($this->notificationPushRepository->delete($entity->id)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('notification_push.delete.success')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::INTERNAL_ERR,
            message: __('notification_push.delete.failed')
        );
    }
}
