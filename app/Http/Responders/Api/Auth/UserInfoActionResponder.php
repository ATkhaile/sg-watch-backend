<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Entity\UserInfoEntity;
use App\Http\Resources\Api\Auth\UserInfoActionResource;

final class UserInfoActionResponder
{
    public function __invoke(UserInfoEntity $userInfoEntity): UserInfoActionResource
    {
        return new UserInfoActionResource([
            'user_info' => $userInfoEntity
        ]);
    }
}
