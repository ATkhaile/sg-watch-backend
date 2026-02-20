<?php

namespace App\Domain\Auth\Entity;

class ResetPasswordRequestEntity
{
    private function __construct(
        private string $token,
        private string $password
    ) {
    }

    public static function create(string $token, string $password): self
    {
        return new self(
            token: $token,
            password: $password
        );
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
