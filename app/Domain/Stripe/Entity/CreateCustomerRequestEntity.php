<?php

namespace App\Domain\Stripe\Entity;

class CreateCustomerRequestEntity
{
    public function __construct(
        public readonly string $payload,
        public readonly string $signature,
        public readonly string $endpointSecret
    ) {
    }

    public function toArray(): array
    {
        return [
            'payload' => $this->payload,
            'signature' => $this->signature,
            'endpoint_secret' => $this->endpointSecret
        ];
    }
}
