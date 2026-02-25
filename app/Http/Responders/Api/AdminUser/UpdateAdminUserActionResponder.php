<?php

namespace App\Http\Responders\Api\AdminUser;

use App\Http\Resources\Api\AdminUser\UpdateAdminUserActionResource;

final class UpdateAdminUserActionResponder
{
    public function __invoke(array $result): UpdateAdminUserActionResource
    {
        return new UpdateAdminUserActionResource($result);
    }
}
