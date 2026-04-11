<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\GetPublicPostListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Post\GetPublicPostListRequest;
use App\Http\Resources\Api\Post\GetPublicPostListActionResource;
use App\Http\Responders\Api\Post\GetPublicPostListActionResponder;

class GetPublicPostListAction extends BaseController
{
    private GetPublicPostListUseCase $useCase;
    private GetPublicPostListActionResponder $responder;

    public function __construct(
        GetPublicPostListUseCase $useCase,
        GetPublicPostListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetPublicPostListRequest $request): GetPublicPostListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
