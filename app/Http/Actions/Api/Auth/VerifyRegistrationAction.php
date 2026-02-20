<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\VerifyRegistrationUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class VerifyRegistrationAction extends BaseController
{
    public function __construct(
        private VerifyRegistrationUseCase $verifyRegistrationUseCase,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(string $token): ActionResource
    {
        $statusEntity = $this->verifyRegistrationUseCase->__invoke($token);
        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
