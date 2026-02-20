<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\DeleteAvatarUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Auth\DeleteAvatarActionResource;
use App\Http\Responders\Api\Auth\DeleteAvatarActionResponder;

class DeleteAvatarAction extends BaseController
{
    private DeleteAvatarUseCase $deleteAvatarUseCase;
    private DeleteAvatarActionResponder $responder;

    public function __construct(
        DeleteAvatarUseCase $deleteAvatarUseCase,
        DeleteAvatarActionResponder $responder
    ) {
        $this->deleteAvatarUseCase = $deleteAvatarUseCase;
        $this->responder = $responder;
    }

    public function __invoke(): DeleteAvatarActionResource
    {
        $this->deleteAvatarUseCase->__invoke();

        return $this->responder->__invoke();
    }
}
