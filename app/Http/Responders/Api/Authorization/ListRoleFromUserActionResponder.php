<?php

namespace App\Http\Responders\Api\Authorization;

use App\Http\Resources\Api\Authorization\ListRoleFromUserActionResource;

class ListRoleFromUserActionResponder
{
    public function __invoke(array $data): ListRoleFromUserActionResource
    {
        return new ListRoleFromUserActionResource($data);
    }
}
