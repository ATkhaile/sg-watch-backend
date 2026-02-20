<?php

namespace App\Domain\NotificationPush\Entity;

class StatusEntity
{
    public function __construct(
        public int $statusCode,
        public string $message
    ) {}
}
