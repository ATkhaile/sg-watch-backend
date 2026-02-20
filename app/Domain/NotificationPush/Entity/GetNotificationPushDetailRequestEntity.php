<?php

namespace App\Domain\NotificationPush\Entity;

class GetNotificationPushDetailRequestEntity
{
    public function __construct(
        public readonly int $id
    ) {}
}
