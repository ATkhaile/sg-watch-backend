<?php

namespace App\Http\Actions\Api\ShopBrand;

use App\Domain\ShopBrand\UseCase\GetShopBrandListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopBrand\GetShopBrandListRequest;
use App\Http\Resources\Api\ShopBrand\GetShopBrandListActionResource;
use App\Http\Responders\Api\ShopBrand\GetShopBrandListActionResponder;

class GetShopBrandListAction extends BaseController
{
    private GetShopBrandListUseCase $useCase;
    private GetShopBrandListActionResponder $responder;

    public function __construct(
        GetShopBrandListUseCase $useCase,
        GetShopBrandListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetShopBrandListRequest $request): GetShopBrandListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
