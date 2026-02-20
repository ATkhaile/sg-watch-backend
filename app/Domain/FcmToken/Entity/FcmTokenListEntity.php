<?php

namespace App\Domain\FcmToken\Entity;

class FcmTokenListEntity
{
    public function __construct(
        public array $fcmTokens = [],
        public int $statusCode = 200
    ) {}
}
