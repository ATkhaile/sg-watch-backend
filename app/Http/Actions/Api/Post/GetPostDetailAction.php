<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\GetPostDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Post\GetPostDetailActionResource;
use App\Http\Responders\Api\Post\GetPostDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetPostDetailAction extends BaseController
{
    private GetPostDetailUseCase $useCase;
    private GetPostDetailActionResponder $responder;

    public function __construct(
        GetPostDetailUseCase $useCase,
        GetPostDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetPostDetailActionResource|JsonResponse
    {
        $post = $this->useCase->__invoke($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($post);
    }
}
