<?php

namespace App\Domain\Google\Entity;


readonly class SessionAppLoginEntity
{
    public function __construct(
        public ?string $token = null,
        public int $statusCode = 200,
        public string $message = '',
    ) {
    }
}