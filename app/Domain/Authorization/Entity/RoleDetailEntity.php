<?php

namespace App\Domain\Authorization\Entity;

class RoleDetailEntity
{
    public function __construct(
        private ?int $id = null,
        private ?string $name = null,
        private ?string $displayName = null,
        private ?string $description = null,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
        private ?string $deletedAt = null,
        private int $statusCode = 0
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->name !== null) {
            $result['name'] = $this->name;
        }

        if ($this->displayName !== null) {
            $result['display_name'] = $this->displayName;
        }

        if ($this->description !== null) {
            $result['description'] = $this->description;
        }

        if ($this->createdAt !== null) {
            $result['created_at'] = $this->createdAt;
        }

        if ($this->updatedAt !== null) {
            $result['updated_at'] = $this->updatedAt;
        }

        if ($this->deletedAt !== null) {
            $result['deleted_at'] = $this->deletedAt;
        }

        return $result;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setDeletedAt(?string $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function setStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
