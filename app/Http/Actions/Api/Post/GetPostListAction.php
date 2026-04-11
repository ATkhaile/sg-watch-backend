<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\GetPostListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Post\GetPostListRequest;
use App\Http\Resources\Api\Post\GetPostListActionResource;
use App\Http\Responders\Api\Post\GetPostListActionResponder;

class GetPostListAction extends BaseController
{
    private GetPostListUseCase $useCase;
    private GetPostListActionResponder $responder;

    public function __construct(
        GetPostListUseCase $useCase,
        GetPostListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetPostListRequest $request): GetPostListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
