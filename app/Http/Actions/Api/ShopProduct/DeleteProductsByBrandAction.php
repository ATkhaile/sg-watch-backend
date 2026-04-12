<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\DeleteProductsByBrandUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\DeleteProductsByBrandActionResource;
use App\Http\Responders\Api\ShopProduct\DeleteProductsByBrandActionResponder;

class DeleteProductsByBrandAction extends BaseController
{
    private DeleteProductsByBrandUseCase $useCase;
    private DeleteProductsByBrandActionResponder $responder;

    public function __construct(
        DeleteProductsByBrandUseCase $useCase,
        DeleteProductsByBrandActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $brandId): DeleteProductsByBrandActionResource
    {
        $result = $this->useCase->__invoke($brandId);

        return $this->responder->__invoke($result);
    }
}
