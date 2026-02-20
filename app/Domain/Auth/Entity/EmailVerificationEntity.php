<?php

namespace App\Domain\Auth\Entity;

use Carbon\Carbon;

class EmailVerificationEntity
{
    public function __construct(
        private string $email,
        private string $firstName,
        private string $lastName,
        private string $password,
        private string $token,
        private Carbon $expiresAt,
        private ?Carbon $createdAt = null,
        private ?Carbon $updatedAt = null,
        private ?int $inviterId = null,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): Carbon
    {
        return $this->expiresAt;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function getInviterId(): ?int
    {
        return $this->inviterId;
    }
}
