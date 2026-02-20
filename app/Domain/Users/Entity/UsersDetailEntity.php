<?php

namespace App\Domain\Users\Entity;

class UsersDetailEntity
{
    public function __construct(
        private ?int $id = null,
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $email = null,
        private ?string $password = null,
        private ?string $role = null,
        private ?string $gender = null,
        private ?string $avatarUrl = null,
        private ?bool $isSystemAdmin = null,
        private ?string $inviteCode = null,
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

        if ($this->firstName !== null) {
            $result['first_name'] = $this->firstName;
        }

        if ($this->lastName !== null) {
            $result['last_name'] = $this->lastName;
        }

        if ($this->email !== null) {
            $result['email'] = $this->email;
        }

        if ($this->role !== null) {
            $result['role'] = $this->role;
        }

        $result['gender'] = $this->gender;
        $result['avatar_url'] = $this->avatarUrl;
        $result['is_system_admin'] = $this->isSystemAdmin;
        $result['invite_code'] = $this->inviteCode;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function getIsSystemAdmin(): ?bool
    {
        return $this->isSystemAdmin;
    }

    public function getInviteCode(): ?string
    {
        return $this->inviteCode;
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

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    public function setIsSystemAdmin(?bool $isSystemAdmin): self
    {
        $this->isSystemAdmin = $isSystemAdmin;
        return $this;
    }

    public function setInviteCode(?string $inviteCode): self
    {
        $this->inviteCode = $inviteCode;
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
