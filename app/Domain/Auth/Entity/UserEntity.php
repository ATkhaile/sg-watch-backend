<?php

namespace App\Domain\Auth\Entity;

class UserEntity implements \JsonSerializable
{
    public function __construct(
        private ?int $id = null,
        private ?string $email = null,
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?bool $isSystemAdmin = null,
        private ?string $password = null,
        private ?string $avatarUrl = null,
        private ?string $resetPasswordToken = null,
        private ?string $resetPasswordTokenExpire = null,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
        private ?string $inviteCode = null,
        private ?int $inviterId = null,
        private ?string $invitedAt = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->email !== null) {
            $result['email'] = $this->email;
        }

        if ($this->firstName !== null) {
            $result['first_name'] = $this->firstName;
        }

        if ($this->lastName !== null) {
            $result['last_name'] = $this->lastName;
        }

        if ($this->isSystemAdmin !== null) {
            $result['is_system_admin'] = $this->isSystemAdmin;
        }

        if ($this->createdAt !== null) {
            $result['created_at'] = $this->createdAt;
        }

        if ($this->updatedAt !== null) {
            $result['updated_at'] = $this->updatedAt;
        }

        return $result;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function isSystemAdmin(): ?bool
    {
        return $this->isSystemAdmin;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function getResetPasswordTokenExpire(): ?string
    {
        return $this->resetPasswordTokenExpire;
    }

    public function getInviteCode(): ?string
    {
        return $this->inviteCode;
    }

    public function getInviterId(): ?int
    {
        return $this->inviterId;
    }

    public function getInvitedAt(): ?string
    {
        return $this->invitedAt;
    }

    public function getFullName(): string
    {
        return trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? ''));
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
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

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setResetPasswordToken(?string $token): self
    {
        $this->resetPasswordToken = $token;
        return $this;
    }

    public function setResetPasswordTokenExpire(?string $expire): self
    {
        $this->resetPasswordTokenExpire = $expire;
        return $this;
    }

    public function setInviteCode(?string $inviteCode): self
    {
        $this->inviteCode = $inviteCode;
        return $this;
    }

    public function setInviterId(?int $inviterId): self
    {
        $this->inviterId = $inviterId;
        return $this;
    }

    public function setInvitedAt(?string $invitedAt): self
    {
        $this->invitedAt = $invitedAt;
        return $this;
    }

}
