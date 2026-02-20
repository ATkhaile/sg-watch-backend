<?php

namespace App\Http\Actions\Api\Comment;

use App\Domain\Comment\UseCase\CreateCommentUseCase;
use App\Domain\Comment\Factory\CreateCommentRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Comment\CreateCommentRequest;
use App\Http\Resources\Api\Comment\ActionResource;
use App\Http\Responders\Api\Comment\ActionResponder;

class CreateCommentAction extends BaseController
{
    public function __construct(
        private CreateCommentUseCase $createCommentUseCase,
        private CreateCommentRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(CreateCommentRequest $request): ActionResource
    {
        $user = auth()->user();
        $requestEntity = $this->factory->createFromRequest($request, $user->id);

        $statusEntity = $this->createCommentUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
