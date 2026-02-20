<?php

namespace App\Domain\NotificationPush\Entity;

class NotificationPushHistoryEntity
{
    public function __construct(
        public ?int $page = null,
        public ?int $limit = null,
        public array $histories = [],
        public array $pagination = [],
        public int $statusCode = 200,
    ) {}
}
