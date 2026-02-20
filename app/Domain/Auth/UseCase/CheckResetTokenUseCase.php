<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Exceptions\Domain\ErrorResourceException;
use App\Enums\StatusCode;
use App\Domain\Auth\Entity\StatusEntity;

final class CheckResetTokenUseCase
{
    private UserRepository $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function __invoke(string $token): StatusEntity
    {
        try {
            $user = $this->user->checkToken($token);
            if (!$user) {
                throw new ErrorResourceException;
            }
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.reset_token.success')
            );
        } catch (ErrorResourceException $e) {
            throw new ErrorResourceException(message: $e->getMessage());
        }

        return new StatusEntity(
            statusCode: StatusCode::INTERNAL_ERR,
            message: __('auth.reset_token.invalid')
        );
    }
}
