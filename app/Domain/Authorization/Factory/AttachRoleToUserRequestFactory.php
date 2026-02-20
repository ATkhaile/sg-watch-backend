<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\AttachRoleToUserRequestEntity;
use App\Http\Requests\Api\Authorization\AttachRoleToUserRequest;

class AttachRoleToUserRequestFactory
{
    public function createFromRequest(AttachRoleToUserRequest $request, string $userId): AttachRoleToUserRequestEntity
    {
        return new AttachRoleToUserRequestEntity(
            (int) $userId,
            $request->input('role_ids', [])
        );
    }
}
