<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\SendPasswordOtpRequestEntity;
use App\Domain\Auth\Entity\StatusEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Enums\StatusCode;
use App\Mail\PasswordResetOtp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class SendPasswordOtpUseCase
{
    public function __construct(
        private UserRepository $user
    ) {
    }

    public function __invoke(SendPasswordOtpRequestEntity $requestEntity): StatusEntity
    {
        try {
            $userEntity = $this->user->findByEmail($requestEntity->getEmail());

            // Luôn trả success để tránh lộ email có tồn tại hay không
            if (!$userEntity) {
                return new StatusEntity(
                    statusCode: StatusCode::OK,
                    message: __('auth.password_otp.sent'),
                );
            }

            // Invalidate tất cả OTP cũ chưa dùng của email này
            $this->user->invalidatePasswordOtps($requestEntity->getEmail());

            // Tạo OTP 6 số
            $codeLength = config('auth.password_otp.code_length', 6);
            $code = str_pad((string) random_int(0, (int) str_repeat('9', $codeLength)), $codeLength, '0', STR_PAD_LEFT);

            // Lưu OTP vào DB
            $expiresIn = config('auth.password_otp.expires_in', 200);
            $this->user->createPasswordOtp(
                $userEntity->getId(),
                $requestEntity->getEmail(),
                $code,
                $expiresIn
            );

            // Gửi email chứa OTP
            Mail::to($requestEntity->getEmail())->send(
                new PasswordResetOtp(
                    code: $code,
                    name: $userEntity->getFullName()
                )
            );

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.password_otp.sent'),
            );
        } catch (\Exception $e) {
            Log::error('Send password OTP error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: $e->getMessage(),
            );
        }
    }
}
