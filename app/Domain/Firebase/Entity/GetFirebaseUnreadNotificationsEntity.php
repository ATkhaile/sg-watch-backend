<?php

namespace App\Domain\Firebase\Entity;

class GetFirebaseUnreadNotificationsEntity
{
    public function __construct(
        public readonly string $fcmToken,
        public array $notifications = [],
        public int $statusCode = 200
    ) {
    }

    public function getFcmToken(): string
    {
        return $this->fcmToken;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
