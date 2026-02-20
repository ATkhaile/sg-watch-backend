<?php

namespace App\Http\Responders\Api\Authorization;

use App\Http\Resources\Api\Authorization\ListPermissionFromRoleActionResource;

class ListPermissionFromRoleActionResponder
{
    public function __invoke(array $data): ListPermissionFromRoleActionResource
    {
        return new ListPermissionFromRoleActionResource($data);
    }
}
