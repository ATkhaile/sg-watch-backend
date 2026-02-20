<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\DeletePermissionRequestEntity;
use App\Http\Requests\Api\Authorization\DeletePermissionRequest;

class DeletePermissionRequestFactory
{
    public function createFromRequest(DeletePermissionRequest $request): DeletePermissionRequestEntity
    {
        return new DeletePermissionRequestEntity(
            id: (int)$request->route('id')
        );
    }
}
