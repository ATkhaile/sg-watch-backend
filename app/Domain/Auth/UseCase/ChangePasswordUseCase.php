<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\UpdateCurrentPasswordRequestEntity;
use App\Domain\Auth\Entity\StatusEntity;
use App\Enums\StatusCode;

final class ChangePasswordUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(UpdateCurrentPasswordRequestEntity $requestEntity): StatusEntity
    {
        try {
            $user = $this->userRepository->findByEmail($requestEntity->getEmail());

            if (!$user) {
                return new StatusEntity(
                    statusCode: StatusCode::NOT_FOUND,
                    message: __('auth.notfound')
                );
            }

            if (!auth()->validate([
                'email' => $requestEntity->getEmail(),
                'password' => $requestEntity->getOldPassword()
            ])) {
                return new StatusEntity(
                    statusCode: StatusCode::UNAUTHORIZED,
                    message: __('auth.password')
                );
            }

            if (!$this->userRepository->updateCurrentPassword($user, $requestEntity->getNewPassword())) {
                return new StatusEntity(
                    statusCode: StatusCode::INTERNAL_ERR,
                    message: __('auth.change_password.failed')
                );
            }

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.change_password.success')
            );
        } catch (\Exception $e) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: $e->getMessage()
            );
        }
    }
}
