<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\ResetPasswordRequestEntity;
use App\Enums\StatusCode;
use App\Domain\Auth\Entity\StatusEntity;
use Illuminate\Support\Facades\Log;

final class ResetPasswordUseCase
{
    private UserRepository $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function __invoke(ResetPasswordRequestEntity $requestEntity): StatusEntity
    {
        try {
            $user = $this->user->getUserByToken($requestEntity->getToken());

            if (!$user) {
                return new StatusEntity(
                    statusCode: StatusCode::NOT_FOUND,
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
            Log::error('Reset password error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: $e->getMessage(),
            );
        }
    }
}
