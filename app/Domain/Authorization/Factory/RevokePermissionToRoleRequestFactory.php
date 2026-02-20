<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\RevokePermissionToRoleRequestEntity;
use App\Http\Requests\Api\Authorization\RevokePermissionToRoleRequest;

class RevokePermissionToRoleRequestFactory
{
    public function createFromRequest(RevokePermissionToRoleRequest $request, string $roleId): RevokePermissionToRoleRequestEntity
    {
        return new RevokePermissionToRoleRequestEntity(
            (int) $roleId,
            $request->input('permission_ids', [])
        );
    }
}
