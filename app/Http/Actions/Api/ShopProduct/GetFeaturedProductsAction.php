<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\GetFeaturedProductsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\GetFeaturedProductsActionResource;
use App\Http\Responders\Api\ShopProduct\GetFeaturedProductsActionResponder;

class GetFeaturedProductsAction extends BaseController
{
    private GetFeaturedProductsUseCase $useCase;
    private GetFeaturedProductsActionResponder $responder;

    public function __construct(
        GetFeaturedProductsUseCase $useCase,
        GetFeaturedProductsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(): GetFeaturedProductsActionResource
    {
        $products = $this->useCase->__invoke();

        return $this->responder->__invoke($products);
    }
}
