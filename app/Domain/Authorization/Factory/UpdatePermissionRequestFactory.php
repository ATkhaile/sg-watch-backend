<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\UpdatePermissionRequestEntity;
use App\Http\Requests\Api\Authorization\UpdatePermissionRequest;

class UpdatePermissionRequestFactory
{
    public function createFromRequest(UpdatePermissionRequest $request): UpdatePermissionRequestEntity
    {
        return new UpdatePermissionRequestEntity(
            name: $request->has('name') ? $request->input('name') : null,
            displayName: $request->has('display_name') ? $request->input('display_name') : null,
            description: $request->has('description') ? $request->input('description') : null,
            domain: $request->has('domain') ? $request->input('domain') : null,
        );
    }
}
