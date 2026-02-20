<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\ResetPasswordWithTokenRequestEntity;
use App\Domain\Auth\Entity\StatusEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Log;

final class ResetPasswordByTokenUseCase
{
    public function __construct(
        private UserRepository $user
    ) {
    }

    public function __invoke(ResetPasswordWithTokenRequestEntity $requestEntity): StatusEntity
    {
        try {
            $user = $this->user->getUserByToken($requestEntity->getResetToken());

            if (!$user) {
                return new StatusEntity(
                    statusCode: StatusCode::BAD_REQUEST,
                    message: __('auth.reset_password.notfound'),
                );
            }

            if (!$this->user->changePassword($user, $requestEntity->getPassword())) {
                return new StatusEntity(
                    statusCode: StatusCode::INTERNAL_ERR,
                    message: __('auth.reset_password.failed'),
                );
            }

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.reset_password.success'),
            );
        } catch (\Exception $e) {
            Log::error('Reset password by token error:', [
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
