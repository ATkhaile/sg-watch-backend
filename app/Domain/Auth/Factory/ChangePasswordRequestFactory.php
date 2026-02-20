<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\ChangePasswordRequestEntity;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;

class ChangePasswordRequestFactory
{
    public function createFromRequest(ChangePasswordRequest $request): ChangePasswordRequestEntity
    {
        return new ChangePasswordRequestEntity(
            oldPassword: $request->password_old,
            newPassword: $request->password
        );
    }
}
