<?php

namespace App\Domain\Authorization\Entity;

class GetPermissionDetailRequestEntity
{
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
