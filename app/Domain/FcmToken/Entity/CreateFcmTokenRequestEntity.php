<?php

namespace App\Domain\FcmToken\Entity;

class CreateFcmTokenRequestEntity
{
    public function __construct(
        public readonly string $fcm_token,
        public readonly ?int $user_id = null,
        public readonly ?string $device_name = null,
        public readonly ?string $app_version_name = null,
        public readonly ?string $app_id = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'fcm_token' => $this->fcm_token,
            'device_name'   => $this->device_name,
            'app_version_name' => $this->app_version_name,
            'app_id' => $this->app_id,
        ];
    }
}
