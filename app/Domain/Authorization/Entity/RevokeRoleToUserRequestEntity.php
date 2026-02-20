<?php

namespace App\Domain\Authorization\Entity;

class RevokeRoleToUserRequestEntity
{
    public function __construct(
        private int $userId,
        private array $roleIds
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
