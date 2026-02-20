<?php

namespace App\Domain\StripeAccount\Entity;

class TestStripeConnectionRequestEntity
{
    public function __construct(
        public readonly string $publicKey,
        public readonly string $secretKey,
        public readonly ?string $webhookSecret = null,
    ) {}
}
