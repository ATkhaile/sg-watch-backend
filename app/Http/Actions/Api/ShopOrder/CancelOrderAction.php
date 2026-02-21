<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\CancelOrderUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\CancelOrderRequest;
use App\Http\Resources\Api\ShopOrder\CancelOrderActionResource;
use App\Http\Responders\Api\ShopOrder\CancelOrderActionResponder;

class CancelOrderAction extends BaseController
{
    private CancelOrderUseCase $useCase;
    private CancelOrderActionResponder $responder;

    public function __construct(
        CancelOrderUseCase $useCase,
        CancelOrderActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CancelOrderRequest $request, int $id): CancelOrderActionResource
    {
        $userId = (int) auth()->user()->id;
        $reason = $request->validated('cancel_reason');

        $result = $this->useCase->__invoke($userId, $id, $reason);

        return $this->responder->__invoke($result);
    }
}
