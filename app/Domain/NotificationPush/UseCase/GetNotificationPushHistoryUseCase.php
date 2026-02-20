<?php

namespace App\Domain\NotificationPush\UseCase;

use App\Components\CommonComponent;
use App\Domain\NotificationPush\Entity\NotificationPushHistoryEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetNotificationPushHistoryUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-notification-pushs';
    public function __construct(
        private NotificationPushRepository $repository
    ) {}

    public function __invoke(NotificationPushHistoryEntity $entity, int $notificationPushId): NotificationPushHistoryEntity
    {
        $this->authorize();

        $data = $this->repository->getAllNotificationPushHistory($entity, $notificationPushId);

        $entity->histories = $data->items();
        $entity->pagination = CommonComponent::getPaginationData($data);
        $entity->statusCode = StatusCode::OK;

        return $entity;
    }
}
