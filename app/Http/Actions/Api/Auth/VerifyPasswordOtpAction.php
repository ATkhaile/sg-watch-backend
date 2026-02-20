<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\Factory\PasswordOtpFactory;
use App\Domain\Auth\UseCase\VerifyPasswordOtpUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\VerifyPasswordOtpRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class VerifyPasswordOtpAction extends BaseController
{
    public function __construct(
        private VerifyPasswordOtpUseCase $useCase,
        private PasswordOtpFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(VerifyPasswordOtpRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createVerifyRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);

        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
