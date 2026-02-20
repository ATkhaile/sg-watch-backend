<?php

namespace App\Domain\Authorization\Entity;

class ListPermissionFromRoleRequestEntity
{
    private ?int $role_id;

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function setRoleId(?int $role_id): void
    {
        $this->role_id = $role_id;
    }
}
