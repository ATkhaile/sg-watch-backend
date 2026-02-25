<?php

namespace App\Http\Responders\Api\AdminUser;

use App\Http\Resources\Api\AdminUser\GetAdminUserListActionResource;

final class GetAdminUserListActionResponder
{
    public function __invoke(array $result): GetAdminUserListActionResource
    {
        return new GetAdminUserListActionResource($result);
    }
}
