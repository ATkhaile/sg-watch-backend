<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\CreateProductUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProduct\CreateProductRequest;
use App\Http\Resources\Api\ShopProduct\CreateProductActionResource;
use App\Http\Responders\Api\ShopProduct\CreateProductActionResponder;

class CreateProductAction extends BaseController
{
    private CreateProductUseCase $useCase;
    private CreateProductActionResponder $responder;

    public function __construct(
        CreateProductUseCase $useCase,
        CreateProductActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateProductRequest $request): CreateProductActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
