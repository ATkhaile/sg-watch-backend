<?php

namespace App\Domain\Authorization\Repository;

use App\Domain\Authorization\Entity\RoleDetailEntity;
use App\Domain\Authorization\Entity\CreateRoleRequestEntity;
use App\Domain\Authorization\Entity\UpdateRoleRequestEntity;
use App\Domain\Authorization\Entity\DeleteRoleRequestEntity;
use App\Domain\Authorization\Entity\RolesEntity;
use App\Domain\Authorization\Entity\ListRoleFromUserRequestEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoleRepository
{
    public function getAllRole(RolesEntity $entity): LengthAwarePaginator;
    public function getRoleDetail(string $roleId): ?RoleDetailEntity;
    public function store(CreateRoleRequestEntity $requestEntity): bool;
    public function update(UpdateRoleRequestEntity $requestEntity, string $roleId): bool;
    public function delete(DeleteRoleRequestEntity $requestEntity): bool;
    public function attachToUser(int $userId, array $roleIds): bool;
    public function revokeToUser(int $userId, array $roleIds): bool;
    public function listRoleFromUser(ListRoleFromUserRequestEntity $requestEntity): Collection;
}
