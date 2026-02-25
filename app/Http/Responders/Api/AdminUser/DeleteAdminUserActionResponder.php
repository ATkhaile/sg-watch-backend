<?php

namespace App\Http\Responders\Api\AdminUser;

use App\Http\Resources\Api\AdminUser\DeleteAdminUserActionResource;

final class DeleteAdminUserActionResponder
{
    public function __invoke(array $result): DeleteAdminUserActionResource
    {
        return new DeleteAdminUserActionResource($result);
    }
}
