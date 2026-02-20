<?php

namespace App\Domain\Auth\Entity;

final class AuthEntity
{
    private string $token;
    private ?array $user;
    private ?string $message;

    public function __construct(string $token = '', ?array $user = null, ?string $message = null)
    {
        $this->token = $token;
        $this->user = $user;
        $this->message = $message;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): ?array
    {
        return $this->user;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
