<?php

namespace App\Domain\UserProfile\Repository;

use App\Domain\UserProfile\Entity\UpdateUserProfileRequestEntity;
use App\Models\User;

interface UserProfileRepository
{
    public function getUser(int $accountId): ?User;
    public function update(int $accountId, UpdateUserProfileRequestEntity $entity): bool;
}
