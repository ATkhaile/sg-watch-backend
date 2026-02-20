<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\DeleteRoleRequestEntity;
use App\Http\Requests\Api\Authorization\DeleteRoleRequest;

class DeleteRoleRequestFactory
{
    public function createFromRequest(DeleteRoleRequest $request): DeleteRoleRequestEntity
    {
        return new DeleteRoleRequestEntity(
            id: (int)$request->route('id')
        );
    }
}
