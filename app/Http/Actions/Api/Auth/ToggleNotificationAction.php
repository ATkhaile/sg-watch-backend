<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ToggleNotificationUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Auth\ToggleNotificationActionResource;
use App\Http\Responders\Api\Auth\ToggleNotificationActionResponder;
use Illuminate\Http\Request;

class ToggleNotificationAction extends BaseController
{
    private ToggleNotificationUseCase $useCase;
    private ToggleNotificationActionResponder $responder;

    public function __construct(
        ToggleNotificationUseCase $useCase,
        ToggleNotificationActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): ToggleNotificationActionResource
    {
        $userInfo = $this->useCase->__invoke();

        return $this->responder->__invoke($userInfo);
    }
}
