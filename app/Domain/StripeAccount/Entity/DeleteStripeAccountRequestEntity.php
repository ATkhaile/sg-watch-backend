<?php

namespace App\Domain\StripeAccount\Entity;

class DeleteStripeAccountRequestEntity
{
    public function __construct(
        private int $id
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
}
