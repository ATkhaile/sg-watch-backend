<?php

namespace App\Http\Actions\Api\Comment;

use App\Domain\Comment\UseCase\DeleteCommentUseCase;
use App\Domain\Comment\Factory\DeleteCommentRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Comment\DeleteCommentRequest;
use App\Http\Resources\Api\Comment\ActionResource;
use App\Http\Responders\Api\Comment\ActionResponder;

class DeleteCommentAction extends BaseController
{
    public function __construct(
        private DeleteCommentUseCase $deleteCommentUseCase,
        private DeleteCommentRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(int $id, DeleteCommentRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $id);

        $statusEntity = $this->deleteCommentUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
