<?php

namespace App\Domain\Auth\Entity;

class StatusEntity
{
    private int $statusCode;
    private ?string $message;
    private ?string $token;

    public function __construct(
        int $statusCode,
        ?string $message = null,
        ?string $token = null
    ) {
        $this->setStatus($statusCode);
        $this->setMessage($message);
        $this->setToken($token);
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function setStatus(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
