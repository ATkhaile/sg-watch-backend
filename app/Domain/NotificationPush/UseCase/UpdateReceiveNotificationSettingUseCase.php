<?php

namespace App\Domain\NotificationPush\UseCase;

use App\Domain\NotificationPush\Entity\UpdateReceiveNotificationSettingRequestEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Domain\NotificationPush\Entity\StatusEntity;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Auth;
use App\Domain\Shared\Concerns\RequiresPermission;

final class UpdateReceiveNotificationSettingUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-fcm-token-receive-notification';
    public function __construct(
        private NotificationPushRepository $repository
    ) {}

    public function __invoke(UpdateReceiveNotificationSettingRequestEntity $entity): StatusEntity {
        $this->authorize();

        $userId = (int) Auth::guard('api')->id();

        if (!$this->repository->updateReceiveNotificationSetting($entity, $userId)) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('notification_push.update_receive_notification.failed')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::OK,
            message: __('notification_push.update_receive_notification.success')
        );
    }
}
