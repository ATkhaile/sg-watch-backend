<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Entity\UserInfoEntity;
use App\Http\Resources\Api\Auth\ToggleNotificationActionResource;

final class ToggleNotificationActionResponder
{
    public function __invoke(UserInfoEntity $userInfoEntity): ToggleNotificationActionResource
    {
        return new ToggleNotificationActionResource([
            'user_info' => $userInfoEntity
        ]);
    }
}
