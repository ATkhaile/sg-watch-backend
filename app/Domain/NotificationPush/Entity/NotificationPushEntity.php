<?php

namespace App\Domain\NotificationPush\Entity;

class NotificationPushEntity
{
    public function __construct(
        public ?int $page = null,
        public ?int $limit = null,
        public ?string $search = null,
        public ?string $direction = null,
        public ?string $sort = null,
        public array $notificationPushs = [],
        public array $pagination = [],
        public int $statusCode = 200
    ) {}
}
