<?php

namespace App\Domain\Authorization\Entity;

class ListPermissionFromUserRequestEntity
{
    private ?int $user_id;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }
}
