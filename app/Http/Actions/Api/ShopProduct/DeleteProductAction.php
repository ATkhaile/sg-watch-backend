<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\DeleteProductUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\DeleteProductActionResource;
use App\Http\Responders\Api\ShopProduct\DeleteProductActionResponder;

class DeleteProductAction extends BaseController
{
    private DeleteProductUseCase $useCase;
    private DeleteProductActionResponder $responder;

    public function __construct(
        DeleteProductUseCase $useCase,
        DeleteProductActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteProductActionResource
    {
        $result = $this->useCase->__invoke($id);

        return $this->responder->__invoke($result);
    }
}
