<?php

namespace App\Domain\Authorization\Entity;

class UpdatePermissionRequestEntity
{
    public function __construct(
        private ?string $name = null,
        private ?string $displayName = null,
        private ?string $description = null,
        private ?string $domain = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
