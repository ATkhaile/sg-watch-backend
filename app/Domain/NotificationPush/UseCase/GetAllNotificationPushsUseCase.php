<?php

namespace App\Domain\NotificationPush\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Components\CommonComponent;
use App\Domain\NotificationPush\Entity\NotificationPushEntity;
use App\Domain\NotificationPush\Repository\NotificationPushRepository;
use App\Enums\StatusCode;

final class GetAllNotificationPushsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-notification-pushs';

    public function __construct(
        private NotificationPushRepository $notificationPushRepository
    ) {}

    public function __invoke(NotificationPushEntity $entity): NotificationPushEntity
    {
        $this->authorize();

        $data = $this->notificationPushRepository->getAll($entity);

        $entity->notificationPushs = $data->items();
        $entity->pagination = CommonComponent::getPaginationData($data);
        $entity->statusCode = StatusCode::OK;

        return $entity;
    }
}
