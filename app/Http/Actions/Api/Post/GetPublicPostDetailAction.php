<?php

namespace App\Http\Actions\Api\Post;

use App\Domain\Post\UseCase\GetPublicPostDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Post\GetPublicPostDetailActionResource;
use App\Http\Responders\Api\Post\GetPublicPostDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetPublicPostDetailAction extends BaseController
{
    private GetPublicPostDetailUseCase $useCase;
    private GetPublicPostDetailActionResponder $responder;

    public function __construct(
        GetPublicPostDetailUseCase $useCase,
        GetPublicPostDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetPublicPostDetailActionResource|JsonResponse
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
