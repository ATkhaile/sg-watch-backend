<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Google\UseCase\GoogleAppLoginUseCase;
use App\Domain\Auth\Factory\SessionAuthFactory;
use App\Http\Requests\Api\Google\GoogleAppLoginRequest;
use App\Http\Resources\Api\Google\GoogleAppLoginResource;
use App\Http\Responders\Api\Google\GoogleAppLoginResponder;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\SessionAppLoginRequest;
use App\Domain\Auth\UseCase\SessionAppLoginUseCase;
use App\Http\Resources\Api\Auth\LoginActionResource;
use App\Http\Responders\Api\Auth\LoginActionResponder;

class SessionAppLoginAction extends BaseController
{
    public function __construct(
        private SessionAppLoginUseCase $useCase,
        private SessionAuthFactory $factory,
        private LoginActionResponder $responder
    ) {
    }

    public function __invoke(SessionAppLoginRequest $request): LoginActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $responseEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($responseEntity);
    }
}