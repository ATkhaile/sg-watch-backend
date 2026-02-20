<?php

namespace App\Domain\Notifications\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Notifications\Entity\GetNotificationsDetailRequestEntity;
use App\Domain\Notifications\Entity\NotificationDetailEntity;
use App\Domain\Notifications\Repository\NotificationsRepository;

final class GetNotificationsDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-notifications';

    public function __construct(
        private NotificationsRepository $notificationsRepository
    ) {
    }

    public function __invoke(GetNotificationsDetailRequestEntity $entity): NotificationDetailEntity
    {
        $this->authorize();

        return $this->notificationsRepository->getDetail($entity->id);
    }
}
