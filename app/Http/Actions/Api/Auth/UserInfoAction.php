<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UserInfoUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Auth\UserInfoActionResource;
use App\Http\Responders\Api\Auth\UserInfoActionResponder;

class UserInfoAction extends BaseController
{
    private UserInfoUseCase $userInfoUseCase;
    private UserInfoActionResponder $responder;

    public function __construct(
        UserInfoUseCase $userInfoUseCase,
        UserInfoActionResponder $responder
    ) {
        $this->userInfoUseCase = $userInfoUseCase;
        $this->responder = $responder;
    }

    public function __invoke(): UserInfoActionResource
    {
        $userInfo = $this->userInfoUseCase->__invoke();

        return $this->responder->__invoke(userInfoEntity: $userInfo);
    }
}
