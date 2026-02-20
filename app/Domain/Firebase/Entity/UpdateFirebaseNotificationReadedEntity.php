<?php

namespace App\Domain\Firebase\Entity;

class UpdateFirebaseNotificationReadedEntity
{
    public function __construct(
        public readonly string $fcmToken,
    ) {
    }

    public function getFcmToken(): string
    {
        return $this->fcmToken;
    }
}
