<?php

namespace App\Domain\Auth\Entity;

class ForgotPasswordRequestEntity
{
    private function __construct(
        private string $email,
        private ?string $redirectUrl = null,
    ) {
    }

    public static function create(string $email, ?string $redirectUrl = null): self
    {
        return new self(email: $email, redirectUrl: $redirectUrl);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }
}
