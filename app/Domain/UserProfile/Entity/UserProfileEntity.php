<?php

namespace App\Domain\UserProfile\Entity;

class UserProfileEntity
{
    public function __construct(
        public readonly ?int $accountId = null,
        public readonly ?array $profile = null,
        public readonly ?int $statusCode = null,
    ) {
    }
}
