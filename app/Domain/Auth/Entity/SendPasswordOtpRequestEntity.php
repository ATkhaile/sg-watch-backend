<?php

namespace App\Domain\Auth\Entity;

class SendPasswordOtpRequestEntity
{
    private function __construct(
        private string $email
    ) {
    }

    public static function create(string $email): self
    {
        return new self(email: $email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
