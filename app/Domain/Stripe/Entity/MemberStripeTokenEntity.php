<?php

namespace App\Domain\Stripe\Entity;

class MemberStripeTokenEntity
{
    public function __construct(
        public readonly ?string $number = null,
        public readonly ?string $exp_month = null,
        public readonly ?string $exp_year = null,
        public readonly ?string $cvc = null,
        public readonly ?object $token = null,
        public readonly ?int $statusCode = null,
        public readonly ?string $public_key = null,
    ) {
    }
}
