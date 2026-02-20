<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ConfirmEmailChangeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\ConfirmEmailChangeRequest;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class ConfirmEmailChangeAction extends BaseController
{
    public function __construct(
        private ConfirmEmailChangeUseCase $useCase,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(ConfirmEmailChangeRequest $request): ActionResource
    {
        $statusEntity = $this->useCase->__invoke($request->input('token'));
        return $this->responder->__invoke($statusEntity);
    }
}
