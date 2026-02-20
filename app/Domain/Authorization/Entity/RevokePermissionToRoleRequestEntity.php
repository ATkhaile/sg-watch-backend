<?php

namespace App\Domain\Authorization\Entity;

class RevokePermissionToRoleRequestEntity
{
    public function __construct(
        private int $roleId,
        private array $permissionIds
    ) {
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getPermissionIds(): array
    {
        return $this->permissionIds;
    }
}
