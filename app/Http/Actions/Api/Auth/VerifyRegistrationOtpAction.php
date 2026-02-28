<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\VerifyRegistrationOtpUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\VerifyRegistrationOtpRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class VerifyRegistrationOtpAction extends BaseController
{
    public function __construct(
        private VerifyRegistrationOtpUseCase $verifyRegistrationOtpUseCase,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(VerifyRegistrationOtpRequest $request): ActionResource
    {
        $statusEntity = $this->verifyRegistrationOtpUseCase->__invoke(
            $request->input('email'),
            $request->input('code')
        );

        return $this->responder->__invoke($statusEntity);
    }
}
