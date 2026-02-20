<?php

namespace App\Domain\Firebase\UseCase;

use App\Domain\Firebase\Entity\GetFirebaseUnreadNotificationsEntity;
use App\Domain\Firebase\Repository\FirebaseRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetFirebaseUnreadNotificationsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-firebase-unread-notifications';
    public function __construct(
        private FirebaseRepository $firebaseRepository
    ) {
    }

    public function __invoke(GetFirebaseUnreadNotificationsEntity $entity): GetFirebaseUnreadNotificationsEntity
    {
        $this->authorize();

        $notifications = $this->firebaseRepository->getUnreadNotifications($entity);

        $entity->setNotifications($notifications);
        $entity->setStatusCode(StatusCode::OK);

        return $entity;
    }
}
