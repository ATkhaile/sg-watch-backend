<?php

namespace App\Domain\Stripe\Entity;

use App\Enums\StatusCode;

class CancelResponseEntity
{
    public function __construct(
        public readonly StatusCode $statusCode,
        public readonly string $message,
        public readonly ?array $data = null,
        public readonly ?string $redirect = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'status_code' => $this->statusCode->value,
            'message' => $this->message,
            'data' => $this->data,
            'redirect' => $this->redirect
        ];
    }
}
