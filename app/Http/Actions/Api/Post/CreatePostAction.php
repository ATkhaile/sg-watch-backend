<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\CreatePostUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Http\Resources\Api\Post\CreatePostActionResource;
use App\Http\Responders\Api\Post\CreatePostActionResponder;

class CreatePostAction extends BaseController
{
    private CreatePostUseCase $useCase;
    private CreatePostActionResponder $responder;

    public function __construct(
        CreatePostUseCase $useCase,
        CreatePostActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreatePostRequest $request): CreatePostActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media');
        }
        $result = $this->useCase->__invoke($data);
        return $this->responder->__invoke($result);
    }
}
