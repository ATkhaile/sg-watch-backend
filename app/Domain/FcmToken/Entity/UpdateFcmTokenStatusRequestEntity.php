<?php

namespace App\Domain\FcmToken\Entity;

class UpdateFcmTokenStatusRequestEntity
{
    public function __construct(
        public readonly int $fcmTokenId,
        public readonly string $activeStatus,
    ) {}
}
