<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\AttachPermissionToUserRequestEntity;
use App\Http\Requests\Api\Authorization\AttachPermissionToUserRequest;

class AttachPermissionToUserRequestFactory
{
    public function createFromRequest(AttachPermissionToUserRequest $request, string $userId): AttachPermissionToUserRequestEntity
    {
        return new AttachPermissionToUserRequestEntity(
            (int) $userId,
            $request->input('permission_ids', [])
        );
    }
}
