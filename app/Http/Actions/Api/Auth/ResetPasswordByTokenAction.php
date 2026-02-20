<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\Factory\PasswordOtpFactory;
use App\Domain\Auth\UseCase\ResetPasswordByTokenUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\ResetPasswordByTokenRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class ResetPasswordByTokenAction extends BaseController
{
    public function __construct(
        private ResetPasswordByTokenUseCase $useCase,
        private PasswordOtpFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(ResetPasswordByTokenRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createResetRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);

        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
