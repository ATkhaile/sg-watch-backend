<?php

namespace App\Domain\Comment\Entity;

class StatusEntity
{
    private int $statusCode;

    public function __construct(int $statusCode = 0)
    {
        $this->statusCode = $statusCode;
    }

    public function setStatus(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
