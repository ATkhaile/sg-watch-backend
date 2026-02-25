<?php

namespace App\Http\Responders\Api\AdminUser;

use App\Http\Resources\Api\AdminUser\CreateAdminUserActionResource;

final class CreateAdminUserActionResponder
{
    public function __invoke(array $result): CreateAdminUserActionResource
    {
        return new CreateAdminUserActionResource($result);
    }
}
