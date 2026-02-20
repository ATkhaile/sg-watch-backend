<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UpdateAvatarUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\UpdateAvatarRequest;
use App\Http\Resources\Api\Auth\UpdateAvatarActionResource;
use App\Http\Responders\Api\Auth\UpdateAvatarActionResponder;

class UpdateAvatarAction extends BaseController
{
    private UpdateAvatarUseCase $updateAvatarUseCase;
    private UpdateAvatarActionResponder $responder;

    public function __construct(
        UpdateAvatarUseCase $updateAvatarUseCase,
        UpdateAvatarActionResponder $responder
    ) {
        $this->updateAvatarUseCase = $updateAvatarUseCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateAvatarRequest $request): UpdateAvatarActionResource
    {
        $avatarData = $this->updateAvatarUseCase->__invoke($request->file('avatar'));

        return $this->responder->__invoke($avatarData);
    }
}
