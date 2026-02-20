<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\RevokePermissionToUserRequestEntity;
use App\Http\Requests\Api\Authorization\RevokePermissionToUserRequest;

class RevokePermissionToUserRequestFactory
{
    public function createFromRequest(RevokePermissionToUserRequest $request, string $userId): RevokePermissionToUserRequestEntity
    {
        return new RevokePermissionToUserRequestEntity(
            (int) $userId,
            $request->input('permission_ids', [])
        );
    }
}
