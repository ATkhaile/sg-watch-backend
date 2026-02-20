<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\StatusEntity;
use App\Domain\Auth\Entity\VerifyPasswordOtpRequestEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Log;

final class VerifyPasswordOtpUseCase
{
    public function __construct(
        private UserRepository $user
    ) {
    }

    public function __invoke(VerifyPasswordOtpRequestEntity $requestEntity): StatusEntity
    {
        try {
            // Tìm OTP record hợp lệ (chưa dùng, chưa hết hạn)
            $otpRecord = $this->user->findValidPasswordOtp($requestEntity->getEmail());

            if (!$otpRecord) {
                return new StatusEntity(
                    statusCode: StatusCode::BAD_REQUEST,
                    message: __('auth.password_otp.invalid'),
                );
            }

            // Kiểm tra số lần thử
            $maxAttempts = config('auth.password_otp.max_attempts', 5);
            if ($otpRecord['attempts'] >= $maxAttempts) {
                return new StatusEntity(
                    statusCode: StatusCode::TOO_MANY_REQUEST,
                    message: __('auth.password_otp.too_many_attempts'),
                );
            }

            // So sánh OTP
            if ($otpRecord['code'] !== $requestEntity->getOtp()) {
                $this->user->incrementOtpAttempts($otpRecord['id']);

                return new StatusEntity(
                    statusCode: StatusCode::BAD_REQUEST,
                    message: __('auth.password_otp.invalid'),
                );
            }

            // OTP đúng → đánh dấu đã dùng
            $this->user->markOtpAsUsed($otpRecord['id']);

            // Tìm user và tạo reset_token
            $userEntity = $this->user->findByEmail($requestEntity->getEmail());
            if (!$userEntity) {
                return new StatusEntity(
                    statusCode: StatusCode::NOT_FOUND,
                    message: __('auth.password_otp.invalid'),
                );
            }

            // Tạo reset_token và lưu vào user
            $userWithToken = $this->user->generateForgotPasswordToken($userEntity);
            if (!$userWithToken) {
                return new StatusEntity(
                    statusCode: StatusCode::INTERNAL_ERR,
                    message: __('auth.reset_password.failed'),
                );
            }

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.password_otp.verified'),
                token: $userWithToken->getResetPasswordToken(),
            );
        } catch (\Exception $e) {
            Log::error('Verify password OTP error:', [
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
