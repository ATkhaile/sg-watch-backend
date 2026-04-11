<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\UpdatePostUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Resources\Api\Post\UpdatePostActionResource;
use App\Http\Responders\Api\Post\UpdatePostActionResponder;

class UpdatePostAction extends BaseController
{
    private UpdatePostUseCase $useCase;
    private UpdatePostActionResponder $responder;

    public function __construct(
        UpdatePostUseCase $useCase,
        UpdatePostActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdatePostRequest $request, int $id): UpdatePostActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media');
        }
        $result = $this->useCase->__invoke($id, $data);
        return $this->responder->__invoke($result);
    }
}
