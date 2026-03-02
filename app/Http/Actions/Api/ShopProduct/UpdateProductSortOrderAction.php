<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\UpdateProductSortOrderUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProduct\UpdateProductSortOrderRequest;
use App\Http\Resources\Api\ShopProduct\UpdateProductSortOrderActionResource;
use App\Http\Responders\Api\ShopProduct\UpdateProductSortOrderActionResponder;

class UpdateProductSortOrderAction extends BaseController
{
    private UpdateProductSortOrderUseCase $useCase;
    private UpdateProductSortOrderActionResponder $responder;

    public function __construct(
        UpdateProductSortOrderUseCase $useCase,
        UpdateProductSortOrderActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateProductSortOrderRequest $request): UpdateProductSortOrderActionResource
    {
        $validated = $request->validated();
        $result = $this->useCase->__invoke($validated['id'], $validated['display_order']);

        return $this->responder->__invoke($result);
    }
}
