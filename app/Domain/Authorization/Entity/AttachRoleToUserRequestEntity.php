<?php

namespace App\Domain\Authorization\Entity;

class AttachRoleToUserRequestEntity
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
