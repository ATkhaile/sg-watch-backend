<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminGetOrderListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\AdminGetOrderListRequest;
use App\Http\Resources\Api\ShopOrder\AdminGetOrderListActionResource;
use App\Http\Responders\Api\ShopOrder\AdminGetOrderListActionResponder;

class AdminGetOrderListAction extends BaseController
{
    private AdminGetOrderListUseCase $useCase;
    private AdminGetOrderListActionResponder $responder;

    public function __construct(
        AdminGetOrderListUseCase $useCase,
        AdminGetOrderListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminGetOrderListRequest $request): AdminGetOrderListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
