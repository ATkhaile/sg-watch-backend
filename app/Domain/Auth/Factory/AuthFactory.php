<?php

namespace App\Domain\Auth\Factory;

use Illuminate\Http\Request;
use App\Domain\Auth\Entity\AuthRequestEntity;

class AuthFactory
{
    public function createFromEmailRequest(Request $request): AuthRequestEntity
    {
        return AuthRequestEntity::createFromEmail(
            email: $request->input('email'),
            password: $request->input('password')
        );
    }

    public function createFromUserIdRequest(Request $request): AuthRequestEntity
    {
        return AuthRequestEntity::createFromUserId(
            userId: $request->input('user_id'),
            password: $request->input('password')
        );
    }
}
