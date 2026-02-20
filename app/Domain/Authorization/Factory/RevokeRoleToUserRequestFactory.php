<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\RevokeRoleToUserRequestEntity;
use App\Http\Requests\Api\Authorization\RevokeRoleToUserRequest;

class RevokeRoleToUserRequestFactory
{
    public function createFromRequest(RevokeRoleToUserRequest $request, string $userId): RevokeRoleToUserRequestEntity
    {
        return new RevokeRoleToUserRequestEntity(
            (int) $userId,
            $request->input('role_ids', [])
        );
    }
}
