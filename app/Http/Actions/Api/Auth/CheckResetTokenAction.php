<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\CheckResetTokenUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Auth\ActionResource;
use App\Http\Responders\Api\Auth\ActionResponder;

class CheckResetTokenAction extends BaseController
{
    private CheckResetTokenUseCase $checkResetTokenUseCase;
    private ActionResponder $responder;

    public function __construct(
        CheckResetTokenUseCase $checkResetTokenUseCase,
        ActionResponder $responder
    ) {
        $this->checkResetTokenUseCase = $checkResetTokenUseCase;
        $this->responder = $responder;
    }

    public function __invoke(string $token): ActionResource
    {
        $statusEntity = $this->checkResetTokenUseCase->__invoke($token);
        return $this->responder->__invoke(statusEntity: $statusEntity);
    }
}
