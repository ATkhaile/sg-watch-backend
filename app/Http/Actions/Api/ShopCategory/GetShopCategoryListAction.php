<?php

namespace App\Http\Actions\Api\ShopCategory;

use App\Domain\ShopCategory\UseCase\GetShopCategoryListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCategory\GetShopCategoryListRequest;
use App\Http\Resources\Api\ShopCategory\GetShopCategoryListActionResource;
use App\Http\Responders\Api\ShopCategory\GetShopCategoryListActionResponder;

class GetShopCategoryListAction extends BaseController
{
    private GetShopCategoryListUseCase $useCase;
    private GetShopCategoryListActionResponder $responder;

    public function __construct(
        GetShopCategoryListUseCase $useCase,
        GetShopCategoryListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetShopCategoryListRequest $request): GetShopCategoryListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
