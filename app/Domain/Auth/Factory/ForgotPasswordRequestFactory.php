<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\ForgotPasswordRequestEntity;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;

class ForgotPasswordRequestFactory
{
    public function createFromRequest(ForgotPasswordRequest $request): ForgotPasswordRequestEntity
    {
        return ForgotPasswordRequestEntity::create(
            email: $request->input('email'),
            redirectUrl: $request->input('redirect_url'),
        );
    }
}
