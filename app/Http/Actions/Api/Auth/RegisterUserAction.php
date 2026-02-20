<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\RegisterUserUseCase;
use App\Domain\Auth\Factory\RegisterUserRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class RegisterUserAction extends BaseController
{
    public function __construct(
        private RegisterUserUseCase $registerUserUseCase,
        private RegisterUserRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(RegisterUserRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $statusEntity = $this->registerUserUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
