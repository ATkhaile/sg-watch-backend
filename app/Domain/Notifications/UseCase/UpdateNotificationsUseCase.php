<?php

namespace App\Domain\Notifications\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Notifications\Entity\UpdateNotificationsRequestEntity;
use App\Domain\Notifications\Entity\StatusEntity;
use App\Domain\Notifications\Repository\NotificationsRepository;
use App\Enums\StatusCode;

final class UpdateNotificationsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-notifications';

    public function __construct(
        private NotificationsRepository $notificationsRepository
    ) {
    }

    public function __invoke(UpdateNotificationsRequestEntity $entity, int $id): StatusEntity
    {
        $this->authorize();


        if (!$this->notificationsRepository->update($entity, $id)) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('notifications.update.failed')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('notifications.update.success')
            );
        }
    }
}
