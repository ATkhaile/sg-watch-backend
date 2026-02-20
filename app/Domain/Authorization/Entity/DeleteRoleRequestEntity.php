<?php

namespace App\Domain\Authorization\Entity;

class DeleteRoleRequestEntity
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
