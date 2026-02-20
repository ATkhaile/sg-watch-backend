<?php

namespace App\Domain\FcmToken\Repository;

use App\Domain\FcmToken\Entity\CreateFcmTokenRequestEntity;
use App\Domain\FcmToken\Entity\UpdateFcmTokenStatusRequestEntity;
use App\Domain\FcmToken\Entity\DeleteFcmTokenRequestEntity;

interface FcmTokenRepository
{
    public function store(CreateFcmTokenRequestEntity $requestEntity): bool;
    public function getByUserId(int $userId): array;
    public function updateStatus(UpdateFcmTokenStatusRequestEntity $entity): bool;
    public function delete(DeleteFcmTokenRequestEntity $requestEntity): bool;
}
