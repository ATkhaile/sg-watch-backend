<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\ForgotPasswordRequestEntity;
use App\Domain\Auth\Entity\StatusEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Exceptions\Domain\ErrorResourceException;
use Illuminate\Support\Facades\Response;
use App\Mail\ForgotPassword;
use App\Enums\StatusCode;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordUseCase
{
    public function __construct(
        private UserRepository $user
    ) {
    }

    public function __invoke(ForgotPasswordRequestEntity $requestEntity): StatusEntity
    {
        try {
            $userEntity = $this->user->findByEmail($requestEntity->getEmail());

            if (!$userEntity) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.forgot_password.notfound'), StatusCode::NOT_FOUND)
                );
            }

            $userWithToken = $this->user->generateForgotPasswordToken($userEntity);

            if (!$userWithToken) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.forgot_password.failed'), StatusCode::NOT_FOUND)
                );
            }

            $baseUrl = $requestEntity->getRedirectUrl() ?: env('BASE_FRONTEND_URL_FORGET_PASSWORD');
            Mail::to($userWithToken->getEmail())->send(new ForgotPassword([
                'name' => $userWithToken->getFullName(),
                'url' => $baseUrl . '?token=' . $userWithToken->getResetPasswordToken()
            ]));

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.forgot_password.success'),
            );
        } catch (ErrorResourceException $e) {
            throw new HttpResponseException(
                Response::apiError($e->getMessage(), StatusCode::NOT_FOUND)
            );
        }
    }
}
