<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\GetProductDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\GetProductDetailActionResource;
use App\Http\Responders\Api\ShopProduct\GetProductDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetProductDetailAction extends BaseController
{
    private GetProductDetailUseCase $useCase;
    private GetProductDetailActionResponder $responder;

    public function __construct(
        GetProductDetailUseCase $useCase,
        GetProductDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(string $slug): GetProductDetailActionResource|JsonResponse
    {
        $user = auth()->user();
        $userId = $user ? (int) $user->id : null;

        $product = $this->useCase->__invoke($slug, $userId);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
                'status_code' => 404,
            ], 404);
        }

        return $this->responder->__invoke($product);
    }
}
