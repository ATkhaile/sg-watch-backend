<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ChangePasswordUseCase;
use App\Domain\Auth\Factory\UpdateCurrentPasswordRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class ChangePasswordAction extends BaseController
{
    public function __construct(
        private ChangePasswordUseCase $useCase,
        private ActionResponder $responder,
        private UpdateCurrentPasswordRequestFactory $factory
    ) {
    }

    public function __invoke(ChangePasswordRequest $request): ActionResource
    {
        $user = auth()->user();
        $requestEntity = $this->factory->createFromRequest($user, $request);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
