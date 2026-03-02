<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\UpdateFeaturedProductsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProduct\UpdateFeaturedProductsRequest;
use App\Http\Resources\Api\ShopProduct\UpdateFeaturedProductsActionResource;
use App\Http\Responders\Api\ShopProduct\UpdateFeaturedProductsActionResponder;

class UpdateFeaturedProductsAction extends BaseController
{
    private UpdateFeaturedProductsUseCase $useCase;
    private UpdateFeaturedProductsActionResponder $responder;

    public function __construct(
        UpdateFeaturedProductsUseCase $useCase,
        UpdateFeaturedProductsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateFeaturedProductsRequest $request): UpdateFeaturedProductsActionResource
    {
        $result = $this->useCase->__invoke($request->validated()['product_ids']);

        return $this->responder->__invoke($result);
    }
}
