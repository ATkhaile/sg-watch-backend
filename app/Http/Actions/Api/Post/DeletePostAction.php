<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\DeletePostUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Post\DeletePostActionResource;
use App\Http\Responders\Api\Post\DeletePostActionResponder;

class DeletePostAction extends BaseController
{
    private DeletePostUseCase $useCase;
    private DeletePostActionResponder $responder;

    public function __construct(
        DeletePostUseCase $useCase,
        DeletePostActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeletePostActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
