<?php

namespace App\Domain\UserProfile\Entity;

class StatusEntity
{
    public function __construct(
        private int $statusCode,
        private string $message
    ) {
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
