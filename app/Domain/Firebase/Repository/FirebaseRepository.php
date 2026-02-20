<?php

namespace App\Domain\Firebase\Repository;

use App\Domain\Firebase\Entity\FirebaseNotificationEntity;
use App\Domain\Firebase\Entity\UpdateFirebaseNotificationReadedEntity;
use App\Domain\Firebase\Entity\GetFirebaseUnreadNotificationsEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface FirebaseRepository
{
    public function getNotifications(FirebaseNotificationEntity $entity): LengthAwarePaginator;
    public function updateNotificationReaded(UpdateFirebaseNotificationReadedEntity $entity, int $id): bool;
    public function getUnreadNotifications(GetFirebaseUnreadNotificationsEntity $entity): array;
}
