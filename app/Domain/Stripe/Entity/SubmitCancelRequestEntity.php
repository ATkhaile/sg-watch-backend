<?php

namespace App\Domain\Stripe\Entity;

class SubmitCancelRequestEntity
{
    public function __construct(
        public readonly string $requestCode,
        public readonly string $reason,
        public readonly string $password
    ) {
    }

    public function toArray(): array
    {
        return [
            'request_code' => $this->requestCode,
            'reason' => $this->reason,
            'password' => $this->password
        ];
    }
}
