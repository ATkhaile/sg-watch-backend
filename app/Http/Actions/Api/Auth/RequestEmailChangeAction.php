<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\RequestEmailChangeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\RequestEmailChangeRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class RequestEmailChangeAction extends BaseController
{
    public function __construct(
        private RequestEmailChangeUseCase $useCase,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(RequestEmailChangeRequest $request): ActionResource
    {
        $user = auth()->user();
        $verificationBaseUrl = $request->input('verification_url') ?: env('BASE_FRONTEND_URL', '') . 'settings/confirm-email';

        $statusEntity = $this->useCase->__invoke(
            $user,
            $request->input('new_email'),
            $request->input('password'),
            $verificationBaseUrl
        );

        return $this->responder->__invoke($statusEntity);
    }
}
