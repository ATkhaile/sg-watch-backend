<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminUpdateOrderUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\AdminUpdateOrderRequest;
use App\Http\Resources\Api\ShopOrder\AdminUpdateOrderActionResource;
use App\Http\Responders\Api\ShopOrder\AdminUpdateOrderActionResponder;

class AdminUpdateOrderAction extends BaseController
{
    private AdminUpdateOrderUseCase $useCase;
    private AdminUpdateOrderActionResponder $responder;

    public function __construct(
        AdminUpdateOrderUseCase $useCase,
        AdminUpdateOrderActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminUpdateOrderRequest $request, int $id): AdminUpdateOrderActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());

        return $this->responder->__invoke($result);
    }
}
