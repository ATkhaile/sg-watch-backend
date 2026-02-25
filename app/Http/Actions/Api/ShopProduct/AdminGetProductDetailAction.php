<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\AdminGetProductDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\AdminGetProductDetailActionResource;
use App\Http\Responders\Api\ShopProduct\AdminGetProductDetailActionResponder;
use Illuminate\Http\JsonResponse;

class AdminGetProductDetailAction extends BaseController
{
    private AdminGetProductDetailUseCase $useCase;
    private AdminGetProductDetailActionResponder $responder;

    public function __construct(
        AdminGetProductDetailUseCase $useCase,
        AdminGetProductDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): AdminGetProductDetailActionResource|JsonResponse
    {
        $product = $this->useCase->__invoke($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return $this->responder->__invoke($product);
    }
}
