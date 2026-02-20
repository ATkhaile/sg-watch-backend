<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\AuthRequestEntity;
use Illuminate\Http\Request;

class AuthRequestFactory
{
    public function createFromRequest(Request $request): AuthRequestEntity
    {
        return new AuthRequestEntity(
            email: $request->input('email'),
            password: $request->input('password'),
            verificationCode: $request->input('verification_code')
        );
    }
}
