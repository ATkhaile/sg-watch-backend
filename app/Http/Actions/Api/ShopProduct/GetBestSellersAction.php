<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\GetBestSellersUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProduct\GetBestSellersActionResource;
use App\Http\Responders\Api\ShopProduct\GetBestSellersActionResponder;

class GetBestSellersAction extends BaseController
{
    private GetBestSellersUseCase $useCase;
    private GetBestSellersActionResponder $responder;

    public function __construct(
        GetBestSellersUseCase $useCase,
        GetBestSellersActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(): GetBestSellersActionResource
    {
        $products = $this->useCase->__invoke(8);

        return $this->responder->__invoke($products);
    }
}
