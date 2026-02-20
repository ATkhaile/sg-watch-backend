<?php

namespace App\Domain\NotificationPush\Entity;

class DeleteNotificationPushRequestEntity
{
    public function __construct(
        public readonly int $id
    ) {}
}
