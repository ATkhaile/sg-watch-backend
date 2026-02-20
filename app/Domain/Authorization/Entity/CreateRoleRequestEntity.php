<?php

namespace App\Domain\Authorization\Entity;

class CreateRoleRequestEntity
{
    public function __construct(
        public readonly string $name,
        public readonly string $displayName,
        public readonly ?string $description = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'displayName' => $this->displayName,
            'description' => $this->description,
        ];
    }
}
