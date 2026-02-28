<?php

namespace App\Http\Actions\Api\ShopBrand;

use App\Domain\ShopBrand\UseCase\DeleteShopBrandUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopBrand\DeleteShopBrandActionResource;
use App\Http\Responders\Api\ShopBrand\DeleteShopBrandActionResponder;

class DeleteShopBrandAction extends BaseController
{
    private DeleteShopBrandUseCase $useCase;
    private DeleteShopBrandActionResponder $responder;

    public function __construct(
        DeleteShopBrandUseCase $useCase,
        DeleteShopBrandActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteShopBrandActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
