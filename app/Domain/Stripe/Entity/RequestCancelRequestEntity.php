<?php

namespace App\Domain\Stripe\Entity;

class RequestCancelRequestEntity
{
    public function __construct(
        public readonly string $email,
        public readonly string $userId,
        public readonly string $userName
    ) {
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'user_id' => $this->userId,
            'user_name' => $this->userName
        ];
    }
}
