<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\LogoutUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\LogoutRequest;
use App\Http\Resources\Api\Auth\LogoutActionResource;
use App\Http\Responders\Api\Auth\LogoutActionResponder;

class LogoutAction extends BaseController
{
    private LogoutUseCase $logoutUseCase;
    private LogoutActionResponder $responder;

    public function __construct(
        LogoutUseCase $logoutUseCase,
        LogoutActionResponder $responder
    ) {
        $this->logoutUseCase = $logoutUseCase;
        $this->responder = $responder;
    }

    public function __invoke(LogoutRequest $request): LogoutActionResource
    {
        $fcmToken = $request->input('fcm_token');
        $authEntity = $this->logoutUseCase->__invoke($fcmToken);
        return $this->responder->__invoke(authEntity: $authEntity);
    }
}
