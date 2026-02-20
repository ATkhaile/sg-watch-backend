<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ResetPasswordUseCase;
use App\Domain\Auth\Entity\ResetPasswordRequestEntity;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class ResetPasswordAction extends BaseController
{
    public function __construct(
        private ResetPasswordUseCase $resetPasswordUseCase,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(string $token, ResetPasswordRequest $request): ActionResource
    {
        $requestEntity = ResetPasswordRequestEntity::create(
            token: $token,
            password: $request->password
        );

        $statusEntity = $this->resetPasswordUseCase->__invoke($requestEntity);
        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
