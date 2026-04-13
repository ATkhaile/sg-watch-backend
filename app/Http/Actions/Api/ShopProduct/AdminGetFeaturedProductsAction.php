<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\AdminGetFeaturedProductsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\AdminGetFeaturedProductsActionResource;
use App\Http\Responders\Api\ShopProduct\AdminGetFeaturedProductsActionResponder;

class AdminGetFeaturedProductsAction extends BaseController
{
    private AdminGetFeaturedProductsUseCase $useCase;
    private AdminGetFeaturedProductsActionResponder $responder;

    public function __construct(
        AdminGetFeaturedProductsUseCase $useCase,
        AdminGetFeaturedProductsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(): AdminGetFeaturedProductsActionResource
    {
        $user = auth('api')->user() ?? auth()->user();
        $userId = $user ? (int) $user->id : null;

        $products = $this->useCase->__invoke($userId);

        return $this->responder->__invoke($products);
    }
}
