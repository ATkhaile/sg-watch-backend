<?php 

namespace App\Domain\NotificationPush\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\NotificationPush\Entity\GetNotificationPushDetailRequestEntity;
use App\Domain\NotificationPush\Entity\NotificationPushDetailEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;

final class GetNotificationPushDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-notification-push';

    public function __construct(
        private NotificationPushRepository $notificationPushRepository
    ) {}

    public function __invoke(GetNotificationPushDetailRequestEntity $entity): ?NotificationPushDetailEntity
    {
        $this->authorize();

        return $this->notificationPushRepository->getDetail($entity->id);
    }
}


