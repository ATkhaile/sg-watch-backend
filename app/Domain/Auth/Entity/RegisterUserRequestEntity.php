<?php

namespace App\Domain\Auth\Entity;

class RegisterUserRequestEntity
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $password,
        private string $password_confirmation,
        private ?string $inviteCode = null,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->password_confirmation;
    }

    public function getInviteCode(): ?string
    {
        return $this->inviteCode;
    }
}
