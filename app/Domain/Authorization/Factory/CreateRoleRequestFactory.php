<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\CreateRoleRequestEntity;
use App\Http\Requests\Api\Authorization\CreateRoleRequest;

class CreateRoleRequestFactory
{
    public function createFromRequest(CreateRoleRequest $request): CreateRoleRequestEntity
    {
        return new CreateRoleRequestEntity(
            name: $request->input('name'),
            displayName: $request->input('display_name'),
            description: $request->input('description'),
        );
    }
}
