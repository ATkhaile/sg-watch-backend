<?php

namespace App\Http\Responders\Api\AdminUser;

use App\Http\Resources\Api\AdminUser\GetAdminUserDetailActionResource;

final class GetAdminUserDetailActionResponder
{
    public function __invoke(array $user): GetAdminUserDetailActionResource
    {
        return new GetAdminUserDetailActionResource(['user' => $user]);
    }
}
