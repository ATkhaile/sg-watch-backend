<?php

namespace App\Http\Responders\Api\Authorization;

use App\Http\Resources\Api\Authorization\ListPermissionFromUserActionResource;

class ListPermissionFromUserActionResponder
{
    public function __invoke(array $data): ListPermissionFromUserActionResource
    {
        return new ListPermissionFromUserActionResource($data);
    }
}
