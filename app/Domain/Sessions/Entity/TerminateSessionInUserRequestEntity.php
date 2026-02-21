<?php

namespace App\Domain\Sessions\Entity;

class TerminateSessionInUserRequestEntity
{
    public function __construct(
        private readonly int $sessionId,
    ) {
    }

    public function getSessionId(): int
    {
        return $this->sessionId;
    }
}
