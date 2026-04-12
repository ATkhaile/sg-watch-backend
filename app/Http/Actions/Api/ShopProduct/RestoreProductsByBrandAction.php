<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\RestoreProductsByBrandUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\RestoreProductsByBrandActionResource;
use App\Http\Responders\Api\ShopProduct\RestoreProductsByBrandActionResponder;

class RestoreProductsByBrandAction extends BaseController
{
    private RestoreProductsByBrandUseCase $useCase;
    private RestoreProductsByBrandActionResponder $responder;

    public function __construct(
        RestoreProductsByBrandUseCase $useCase,
        RestoreProductsByBrandActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $brandId): RestoreProductsByBrandActionResource
    {
        $result = $this->useCase->__invoke($brandId);

        return $this->responder->__invoke($result);
    }
}
