<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\UsersDetailEntity;
use App\Domain\Users\Entity\CreateUsersRequestEntity;
use App\Domain\Users\Entity\UpdateUsersRequestEntity;
use App\Domain\Users\Entity\DeleteUsersRequestEntity;
use App\Domain\Users\Entity\UsersEntity;
use App\Domain\Users\Entity\GetUsersWithStoriesRequestEntity;
use App\Domain\Users\Entity\UserSessionDevicesEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface UsersRepository
{
    public function getAllUsers(UsersEntity $entity): LengthAwarePaginator;
    public function getUsersDetail(string $userId): ?UsersDetailEntity;
    public function store(CreateUsersRequestEntity $requestEntity): bool;
    public function update(UpdateUsersRequestEntity $requestEntity, string $userId): bool;
    public function delete(DeleteUsersRequestEntity $requestEntity): bool;
    public function getUsersWithStories(GetUsersWithStoriesRequestEntity $entity): LengthAwarePaginator;
    public function getUserSessionDevices(UserSessionDevicesEntity $entity): LengthAwarePaginator;
    public function deleteSessionDevice(int $sessionId): bool;
}
