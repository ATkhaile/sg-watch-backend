<?php

namespace App\Domain\NotificationPush\Repository;

use App\Domain\NotificationPush\Entity\CreateNotificationPushRequestEntity;
use App\Domain\NotificationPush\Entity\NotificationPushDetailEntity;
use App\Domain\NotificationPush\Entity\NotificationPushEntity;
use App\Domain\NotificationPush\Entity\NotificationPushHistoryEntity;
use App\Domain\NotificationPush\Entity\UpdateNotificationPushRequestEntity;
use App\Domain\NotificationPush\Entity\UpdateReceiveNotificationSettingRequestEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationPushRepository
{
    public function getAll(NotificationPushEntity $entity): LengthAwarePaginator;
    public function store(CreateNotificationPushRequestEntity $entity, int $currentUserId): bool;
    public function getDetail(int $id): ?NotificationPushDetailEntity;
    public function update(UpdateNotificationPushRequestEntity $entity, int $id, int $currentUserId): bool;
    public function delete(int $id): bool;
    public function getAllNotificationPushHistory(NotificationPushHistoryEntity $entity, int $notificationPushId): LengthAwarePaginator;
    public function updateReceiveNotificationSetting(UpdateReceiveNotificationSettingRequestEntity $entity, int $userId): bool;
}
