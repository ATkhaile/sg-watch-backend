<?php

namespace App\Domain\NotificationPush\Entity;

class NotificationPushDetailEntity
{
    public function __construct(
        public readonly array $notification_push,
        public readonly int $status_code
    ) {}
}
