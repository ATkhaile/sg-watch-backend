<?php

namespace App\Domain\Authorization\Entity;

class AttachPermissionToUserRequestEntity
{
    public function __construct(
        private int $userId,
        private array $permissionIds
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPermissionIds(): array
    {
        return $this->permissionIds;
    }
}
