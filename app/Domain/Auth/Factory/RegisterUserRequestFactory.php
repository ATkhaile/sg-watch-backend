<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\RegisterUserRequestEntity;
use App\Http\Requests\Api\Auth\RegisterUserRequest;

class RegisterUserRequestFactory
{
    public function createFromRequest(RegisterUserRequest $request): RegisterUserRequestEntity
    {
        return new RegisterUserRequestEntity(
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            email: $request->input('email'),
            password: $request->input('password'),
            password_confirmation: $request->input('password_confirmation'),
            inviteCode: $request->input('invite_code'),
        );
    }
}
