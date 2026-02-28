<?php

namespace App\Http\Actions\Api\ShopCategory;

use App\Domain\ShopCategory\UseCase\GetShopCategoryDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCategory\GetShopCategoryDetailActionResource;
use App\Http\Responders\Api\ShopCategory\GetShopCategoryDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetShopCategoryDetailAction extends BaseController
{
    private GetShopCategoryDetailUseCase $useCase;
    private GetShopCategoryDetailActionResponder $responder;

    public function __construct(
        GetShopCategoryDetailUseCase $useCase,
        GetShopCategoryDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetShopCategoryDetailActionResource|JsonResponse
    {
        $category = $this->useCase->__invoke($id);
        if (!$category) {
            return response()->json([
                'message' => 'Shop category not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($category);
    }
}
