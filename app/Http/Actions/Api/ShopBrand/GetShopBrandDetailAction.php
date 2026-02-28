<?php

namespace App\Http\Actions\Api\ShopBrand;

use App\Domain\ShopBrand\UseCase\GetShopBrandDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopBrand\GetShopBrandDetailActionResource;
use App\Http\Responders\Api\ShopBrand\GetShopBrandDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetShopBrandDetailAction extends BaseController
{
    private GetShopBrandDetailUseCase $useCase;
    private GetShopBrandDetailActionResponder $responder;

    public function __construct(
        GetShopBrandDetailUseCase $useCase,
        GetShopBrandDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetShopBrandDetailActionResource|JsonResponse
    {
        $brand = $this->useCase->__invoke($id);
        if (!$brand) {
            return response()->json([
                'message' => 'Shop brand not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($brand);
    }
}
