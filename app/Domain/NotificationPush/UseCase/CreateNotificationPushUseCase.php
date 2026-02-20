<?php 
namespace App\Domain\NotificationPush\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\NotificationPush\Entity\CreateNotificationPushRequestEntity;
use App\Domain\NotificationPush\Entity\StatusEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Auth;

final class CreateNotificationPushUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-notification-push';

    public function __construct(
        private NotificationPushRepository $notificationPushRepository
    ) {}

    public function __invoke(CreateNotificationPushRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        $currentUserId = Auth::guard('api')->id();

        if ($this->notificationPushRepository->store($entity, $currentUserId)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('notification_push.create.success')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::INTERNAL_ERR,
            message: __('notification_push.create.failed')
        );
    }
}