<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\VerifyLoginUseCase;
use App\Domain\Auth\Factory\VerifyLoginFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\VerifyLoginCodeRequest;
use App\Http\Resources\Api\Auth\LoginActionResource;
use App\Http\Responders\Api\Auth\LoginActionResponder;

class VerifyLoginAction extends BaseController
{
    private VerifyLoginUseCase $verifyLoginUseCase;
    private LoginActionResponder $responder;
    private VerifyLoginFactory $factory;

    public function __construct(
        VerifyLoginUseCase $verifyLoginUseCase,
        LoginActionResponder $responder,
        VerifyLoginFactory $factory
    ) {
        $this->verifyLoginUseCase = $verifyLoginUseCase;
        $this->responder = $responder;
        $this->factory = $factory;
    }

    public function __invoke(VerifyLoginCodeRequest $request): LoginActionResource
    {
        if ($request->has('email')) {
            $requestEntity = $this->factory->createFromEmailRequest($request);
        } else {
            $requestEntity = $this->factory->createFromUserIdRequest($request);
        }

        $authEntity = $this->verifyLoginUseCase->__invoke($requestEntity);

        return $this->responder->__invoke(authEntity: $authEntity);
    }
}
