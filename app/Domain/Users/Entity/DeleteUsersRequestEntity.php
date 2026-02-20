<?php

namespace App\Domain\Users\Entity;

class DeleteUsersRequestEntity
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
