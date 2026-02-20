<?php

namespace App\Domain\Auth\Entity;

class ResetPasswordWithTokenRequestEntity
{
    private function __construct(
        private string $resetToken,
        private string $password
    ) {
    }

    public static function create(string $resetToken, string $password): self
    {
        return new self(resetToken: $resetToken, password: $password);
    }

    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
