<?php

namespace App\Domain\Notifications\Repository;

use App\Domain\Notifications\Entity\CreateNotificationsRequestEntity;
use App\Domain\Notifications\Entity\UpdateNotificationsRequestEntity;
use App\Domain\Notifications\Entity\NotificationDetailEntity;
use App\Domain\Notifications\Entity\NotificationEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationsRepository
{
    public function getAll(NotificationEntity $entity): LengthAwarePaginator;
    public function getDetail(int $notificationId): ?NotificationDetailEntity;
    public function store(CreateNotificationsRequestEntity $entity): bool;
    public function update(UpdateNotificationsRequestEntity $entity, int $id): bool;
    public function delete(int $notificationId): bool;
}
