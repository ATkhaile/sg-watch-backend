<?php

namespace App\Domain\Authorization\Repository;

use App\Domain\Authorization\Entity\PermissionDetailEntity;
use App\Domain\Authorization\Entity\CreatePermissionRequestEntity;
use App\Domain\Authorization\Entity\UpdatePermissionRequestEntity;
use App\Domain\Authorization\Entity\DeletePermissionRequestEntity;
use App\Domain\Authorization\Entity\PermissionsEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PermissionRepository
{
    public function getAllPermission(PermissionsEntity $entity): LengthAwarePaginator;
    public function getPermissionDetail(string $permissionId): ?PermissionDetailEntity;
    public function store(CreatePermissionRequestEntity $requestEntity): bool;
    public function update(UpdatePermissionRequestEntity $requestEntity, string $permissionId): bool;
    public function delete(DeletePermissionRequestEntity $requestEntity): bool;
    public function attachToUser(int $userId, array $permissionIds): bool;
    public function attachToRole(int $roleId, array $permissionIds): bool;
    public function revokeToUser(int $roleId, array $permissionIds): bool;
    public function revokeToRole(int $roleId, array $permissionIds): bool;
    public function listPermissionFromUser(int $userId): array;
    public function listPermissionFromRole(int $roleId): Collection;
    public function getDistinctUsecaseGroups(): Collection;
    public function toggleActive(int $permissionId): ?array;
}
