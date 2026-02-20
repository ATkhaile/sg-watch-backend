<?php

namespace App\Domain\Stripe\Entity;

class GetPortalLinkRequestEntity
{
    public function __construct(
        public readonly string $userEmail
    ) {
    }

    public function toArray(): array
    {
        return [
            'user_email' => $this->userEmail
        ];
    }
}
