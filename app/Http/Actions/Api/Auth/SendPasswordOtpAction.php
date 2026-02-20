<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\Factory\PasswordOtpFactory;
use App\Domain\Auth\UseCase\SendPasswordOtpUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\SendPasswordOtpRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class SendPasswordOtpAction extends BaseController
{
    public function __construct(
        private SendPasswordOtpUseCase $useCase,
        private PasswordOtpFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(SendPasswordOtpRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createSendRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);

        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
