<?php

namespace App\Domain\Auth\Entity;

class SessionAuthRequestEntity
{
    public function __construct(
        public ?string $sessionId,
    ) {
    }
}
