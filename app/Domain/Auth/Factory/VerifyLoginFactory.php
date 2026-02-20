<?php

namespace App\Domain\Auth\Factory;

use Illuminate\Http\Request;
use App\Domain\Auth\Entity\VerifyLoginRequestEntity;

class VerifyLoginFactory
{
    public function createFromEmailRequest(Request $request): VerifyLoginRequestEntity
    {
        return VerifyLoginRequestEntity::createFromEmail(
            email: $request->input('email'),
            verificationCode: $request->input('verification_code')
        );
    }

    public function createFromUserIdRequest(Request $request): VerifyLoginRequestEntity
    {
        return VerifyLoginRequestEntity::createFromUserId(
            userId: $request->input('user_id'),
            verificationCode: $request->input('verification_code')
        );
    }
}
