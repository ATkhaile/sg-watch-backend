<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\CreatePermissionRequestEntity;
use App\Http\Requests\Api\Authorization\CreatePermissionRequest;

class CreatePermissionRequestFactory
{
    public function createFromRequest(CreatePermissionRequest $request): CreatePermissionRequestEntity
    {
        return new CreatePermissionRequestEntity(
            name: $request->input('name'),
            displayName: $request->input('display_name'),
            description: $request->input('description'),
            domain: $request->input('domain'),
        );
    }
}
