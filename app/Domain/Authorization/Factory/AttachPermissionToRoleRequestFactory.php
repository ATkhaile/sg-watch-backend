<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\AttachPermissionToRoleRequestEntity;
use App\Http\Requests\Api\Authorization\AttachPermissionToRoleRequest;

class AttachPermissionToRoleRequestFactory
{
    public function createFromRequest(AttachPermissionToRoleRequest $request, string $roleId): AttachPermissionToRoleRequestEntity
    {
        return new AttachPermissionToRoleRequestEntity(
            (int) $roleId,
            $request->input('permission_ids', [])
        );
    }
}
