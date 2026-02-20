<?php

namespace App\Domain\NotificationPush\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\NotificationPush\Entity\StatusEntity;
use App\Domain\NotificationPush\Entity\UpdateNotificationPushRequestEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Auth;

final class UpdateNotificationPushUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-notification-push';

    public function __construct(
        private NotificationPushRepository $notificationPushRepository
    ) {}

    public function __invoke(UpdateNotificationPushRequestEntity $entity, int $id): StatusEntity
    {
        $this->authorize();

        $currentUserId = Auth::guard('api')->id();

        if (!$this->notificationPushRepository->update($entity, $id, $currentUserId)) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('notification_push.update.failed')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::OK,
            message: __('notification_push.update.success')
        );
    }
}
