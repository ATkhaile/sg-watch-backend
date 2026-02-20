<?php

namespace App\Domain\StripeAccount\Entity;

class StatusEntity
{
    private int $statusCode;
    private ?string $message;

    public function __construct(
        int $statusCode,
        ?string $message = null
    ) {
        $this->setStatus($statusCode);
        $this->setMessage($message);
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
}
