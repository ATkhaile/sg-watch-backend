<?php

namespace App\Http\Actions\Api\Comment;

use App\Domain\Comment\UseCase\GetCommentsByModelUseCase;
use App\Domain\Comment\Factory\GetCommentsByModelRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Comment\GetCommentsByModelRequest;
use App\Http\Resources\Api\Comment\CommentsResource;
use App\Http\Responders\Api\Comment\CommentsResponder;

class GetCommentsByModelAction extends BaseController
{
    public function __construct(
        private GetCommentsByModelUseCase $getCommentsByModelUseCase,
        private GetCommentsByModelRequestFactory $factory,
        private CommentsResponder $responder
    ) {
    }

    public function __invoke(string $model, int $modelId, GetCommentsByModelRequest $request): CommentsResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $entity = $this->getCommentsByModelUseCase->__invoke($model, $modelId, $requestEntity);
        return $this->responder->__invoke($entity);
    }
}
