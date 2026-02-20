<?php

namespace App\Domain\Stripe\Entity;

use App\Enums\StatusCode;

class StatusEntity
{
    public function __construct(
        public readonly StatusCode $statusCode,
        public readonly string $message,
        public readonly mixed $data = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'status_code' => $this->statusCode->value,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
