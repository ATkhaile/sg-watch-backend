<?php

namespace App\Domain\Google\Entity;

readonly class GoogleAppLoginRequestEntity
{
    public function __construct(
        public string $token,
        public ?string $type = null,
    ) {
    }
}