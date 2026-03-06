<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminCreateOrderUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\AdminCreateOrderRequest;
use App\Http\Resources\Api\ShopOrder\AdminCreateOrderActionResource;
use App\Http\Responders\Api\ShopOrder\AdminCreateOrderActionResponder;

class AdminCreateOrderAction extends BaseController
{
    private AdminCreateOrderUseCase $useCase;
    private AdminCreateOrderActionResponder $responder;

    public function __construct(
        AdminCreateOrderUseCase $useCase,
        AdminCreateOrderActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminCreateOrderRequest $request): AdminCreateOrderActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
