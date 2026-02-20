<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\Factory\ForgotPasswordRequestFactory;
use App\Domain\Auth\UseCase\ForgotPasswordUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class ForgotPasswordAction extends BaseController
{
    private ForgotPasswordUseCase $forgotPasswordUseCase;
    private ForgotPasswordRequestFactory $factory;
    private ActionResponder $responder;

    public function __construct(
        ForgotPasswordUseCase $forgotPasswordUseCase,
        ForgotPasswordRequestFactory $factory,
        ActionResponder $responder
    ) {
        $this->forgotPasswordUseCase = $forgotPasswordUseCase;
        $this->factory = $factory;
        $this->responder = $responder;
    }

    public function __invoke(ForgotPasswordRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->forgotPasswordUseCase->__invoke($requestEntity);
        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
