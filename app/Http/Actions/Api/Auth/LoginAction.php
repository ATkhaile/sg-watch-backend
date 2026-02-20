<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\LoginUseCase;
use App\Domain\Auth\Factory\AuthRequestFactory;
use App\Domain\Sessions\UseCase\CreateSessionUseCase;
use App\Domain\Auth\Entity\AuthEntity;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\LoginActionResource;
use App\Http\Responders\Api\Auth\LoginActionResponder;
use App\Domain\Sessions\Entity\CreateSessionRequestEntity;

class LoginAction extends BaseController
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LoginActionResponder $responder,
        private AuthRequestFactory $factory,
        private CreateSessionUseCase $createSessionUseCase
    ) {}

    public function __invoke(LoginRequest $request): LoginActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $result = $this->loginUseCase->__invoke(
            requestEntity: $requestEntity
        );

        if ($result instanceof AuthEntity) {
            $user = auth()->user();
            if ($user) {
                $sessionRequest = new CreateSessionRequestEntity(
                    userId: $user->id,
                    token: $result->getToken(),
                    ipAddress: $request->ip(),
                    userAgent: $request->userAgent(),
                    appId: $request->header('X-App-Id'),
                    domain: $request->header('Origin')
                );
                $this->createSessionUseCase->__invoke($sessionRequest);
            }
        }

        return $this->responder->__invoke($result);
    }
}
