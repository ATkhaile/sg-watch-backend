<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\UpdateProductUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProduct\UpdateProductRequest;
use App\Http\Resources\Api\ShopProduct\UpdateProductActionResource;
use App\Http\Responders\Api\ShopProduct\UpdateProductActionResponder;

class UpdateProductAction extends BaseController
{
    private UpdateProductUseCase $useCase;
    private UpdateProductActionResponder $responder;

    public function __construct(
        UpdateProductUseCase $useCase,
        UpdateProductActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateProductRequest $request, int $id): UpdateProductActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());

        return $this->responder->__invoke($result);
    }
}
