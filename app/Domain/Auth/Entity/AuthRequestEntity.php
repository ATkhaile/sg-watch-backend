<?php

namespace App\Domain\Auth\Entity;

class AuthRequestEntity
{
    public function __construct(
        private string $email,
        private string $password,
        private ?string $verificationCode = null
    ) {
    }

    public function getCredentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }
}
