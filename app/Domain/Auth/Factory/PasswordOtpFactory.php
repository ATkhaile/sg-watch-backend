<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\SendPasswordOtpRequestEntity;
use App\Domain\Auth\Entity\VerifyPasswordOtpRequestEntity;
use App\Domain\Auth\Entity\ResetPasswordWithTokenRequestEntity;
use Illuminate\Http\Request;

class PasswordOtpFactory
{
    public function createSendRequest(Request $request): SendPasswordOtpRequestEntity
    {
        return SendPasswordOtpRequestEntity::create(
            email: $request->input('email')
        );
    }

    public function createVerifyRequest(Request $request): VerifyPasswordOtpRequestEntity
    {
        return VerifyPasswordOtpRequestEntity::create(
            email: $request->input('email'),
            otp: $request->input('otp')
        );
    }

    public function createResetRequest(Request $request): ResetPasswordWithTokenRequestEntity
    {
        return ResetPasswordWithTokenRequestEntity::create(
            resetToken: $request->input('reset_token'),
            password: $request->input('password')
        );
    }
}
