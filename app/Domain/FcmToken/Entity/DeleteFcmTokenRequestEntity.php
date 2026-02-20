<?php

namespace App\Domain\FcmToken\Entity;

class DeleteFcmTokenRequestEntity
{
    public function __construct(
        public readonly string $fcm_token,
    ) {
    }
}
