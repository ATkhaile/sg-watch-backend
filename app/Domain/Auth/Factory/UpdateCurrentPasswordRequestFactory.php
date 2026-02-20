<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\UpdateCurrentPasswordRequestEntity;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Models\User;

class UpdateCurrentPasswordRequestFactory
{
    public function createFromRequest(User $user, ChangePasswordRequest $request): UpdateCurrentPasswordRequestEntity
    {
        return UpdateCurrentPasswordRequestEntity::create(
            email: $user->email,
            oldPassword: $request->password_old,
            newPassword: $request->password
        );
    }
}
