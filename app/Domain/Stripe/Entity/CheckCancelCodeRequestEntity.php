<?php

namespace App\Domain\Stripe\Entity;

class CheckCancelCodeRequestEntity
{
    public function __construct(
        public readonly string $requestCode
    ) {
    }

    public function toArray(): array
    {
        return [
            'request_code' => $this->requestCode
        ];
    }
}
