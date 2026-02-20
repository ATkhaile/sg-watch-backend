<?php

namespace App\Domain\Notifications\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Notifications\Entity\CreateNotificationsRequestEntity;
use App\Domain\Notifications\Entity\StatusEntity;
use App\Domain\Notifications\Repository\NotificationsRepository;
use App\Enums\StatusCode;

final class CreateNotificationsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-notifications';

    public function __construct(
        private NotificationsRepository $notificationsRepository
    ) {
    }

    public function __invoke(CreateNotificationsRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        if ($this->notificationsRepository->store($entity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('notifications.create.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('notifications.create.failed')
            );
        }
    }
}
