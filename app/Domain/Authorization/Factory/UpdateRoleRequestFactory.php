<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\UpdateRoleRequestEntity;
use App\Http\Requests\Api\Authorization\UpdateRoleRequest;

class UpdateRoleRequestFactory
{
    public function createFromRequest(UpdateRoleRequest $request): UpdateRoleRequestEntity
    {
        return new UpdateRoleRequestEntity(
            name: $request->has('name') ? $request->input('name') : null,
            displayName: $request->has('display_name') ? $request->input('display_name') : null,
            description: $request->has('description') ? $request->input('description') : null,
        );
    }
}
