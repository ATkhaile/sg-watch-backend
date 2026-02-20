<?php

namespace App\Domain\Users\Entity;

class UserSessionDevicesEntity
{
    public function __construct(
        public readonly int $user_id,

        public ?string $status = null,      
        public string $sortBy = 'id',         
        public string $sortDirection = 'desc',
        public int $limit = 10,               
        public int $page = 1,

        public array $sessions = [],
        public array $pagination = [],
        public int $statusCode = 0
    ) {}
}
