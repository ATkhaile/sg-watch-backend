<?php

namespace App\Domain\NotificationPush\Entity;

class CreateNotificationPushRequestEntity
{
    public function __construct(
        public readonly string $title,
        public readonly string $message,
        public readonly mixed $img_path = null,
        public readonly bool $all_user_flag,
        public readonly bool $push_now_flag,
        public readonly ?string $push_schedule,
        public readonly array $user_ids = [],
        public readonly ?string $sound = null,
        public readonly ?string $redirect_type = null,
        public readonly ?int $app_page_id = null,
        public readonly mixed $attach_file = null,
        public readonly ?string $attach_link = null,
    ) {}
}
