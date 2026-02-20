<?php

namespace App\Http\Actions\Api\Comment;

use App\Domain\Comment\UseCase\GetAllCommentsUseCase;
use App\Domain\Comment\Factory\GetAllCommentsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Comment\GetAllCommentsRequest;
use App\Http\Resources\Api\Comment\CommentsResource;
use App\Http\Responders\Api\Comment\CommentsResponder;

class GetAllCommentsAction extends BaseController
{
    public function __construct(
        private GetAllCommentsUseCase $getAllCommentsUseCase,
        private GetAllCommentsRequestFactory $factory,
        private CommentsResponder $responder
    ) {
    }

    public function __invoke(GetAllCommentsRequest $request): CommentsResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $entity = $this->getAllCommentsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($entity);
    }
}
