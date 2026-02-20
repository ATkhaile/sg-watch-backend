<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Entity\UserInfoEntity;
use App\Http\Resources\Api\Auth\UpdateProfileActionResource;

final class UpdateProfileActionResponder
{
    public function __invoke(UserInfoEntity $userInfoEntity): UpdateProfileActionResource
    {
        return new UpdateProfileActionResource([
            'user_info' => $userInfoEntity
        ]);
    }
}
