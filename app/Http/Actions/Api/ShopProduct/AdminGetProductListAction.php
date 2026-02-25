<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\AdminGetProductListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProduct\AdminGetProductListRequest;
use App\Http\Resources\Api\ShopProduct\AdminGetProductListActionResource;
use App\Http\Responders\Api\ShopProduct\AdminGetProductListActionResponder;

class AdminGetProductListAction extends BaseController
{
    private AdminGetProductListUseCase $useCase;
    private AdminGetProductListActionResponder $responder;

    public function __construct(
        AdminGetProductListUseCase $useCase,
        AdminGetProductListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminGetProductListRequest $request): AdminGetProductListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
