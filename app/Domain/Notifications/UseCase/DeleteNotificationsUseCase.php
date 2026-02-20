<?php

namespace App\Domain\Notifications\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Notifications\Entity\DeleteNotificationsRequestEntity;
use App\Domain\Notifications\Entity\StatusEntity;
use App\Domain\Notifications\Repository\NotificationsRepository;
use App\Enums\StatusCode;

final class DeleteNotificationsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-notifications';

    public function __construct(
        private NotificationsRepository $notificationsRepository
    ) {
    }

    public function __invoke(DeleteNotificationsRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        if ($this->notificationsRepository->delete($entity->id)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('notifications.delete.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('notifications.delete.failed')
            );
        }
    }
}
