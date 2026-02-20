<?php

namespace App\Domain\Authorization\Entity;

class CreatePermissionRequestEntity
{
    public function __construct(
        public readonly string $name,
        public readonly string $displayName,
        public readonly ?string $description = null,
        public readonly ?string $usecaseGroup = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'displayName' => $this->displayName,
            'description' => $this->description,
            'usecaseGroup' => $this->usecaseGroup,
        ];
    }
}
