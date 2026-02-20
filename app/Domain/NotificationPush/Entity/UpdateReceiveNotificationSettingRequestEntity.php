<?php

namespace App\Domain\NotificationPush\Entity;

class UpdateReceiveNotificationSettingRequestEntity
{
    public function __construct(
        public readonly string $fcmToken,
        public readonly ?bool $receiveNotificationChat,
        public readonly ?bool $receiveReservation,
    ) {}
}
