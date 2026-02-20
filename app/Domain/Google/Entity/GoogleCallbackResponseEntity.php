<?php

namespace App\Domain\Google\Entity;

class GoogleCallbackResponseEntity
{
    public function __construct(
        public readonly ?string $token = null,
        public readonly int $statusCode = 200,
        public readonly ?string $message = null,
        public readonly ?bool $isFirstLogin = false,
    ) {
    }

    public function toArray(): array
    {
        return [
            'status_code' => $this->statusCode,
            'token' => $this->token,
            'message' => $this->message,
            'is_first_login' => $this->isFirstLogin,
        ];
    }
}
