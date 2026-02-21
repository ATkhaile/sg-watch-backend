<?php

namespace App\Http\Actions\Api\ShopCart;

use App\Domain\ShopCart\UseCase\MergeCartUseCase;
use App\Domain\ShopCart\UseCase\GetCartUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCart\MergeCartRequest;
use App\Http\Resources\Api\ShopCart\MergeCartActionResource;
use App\Http\Responders\Api\ShopCart\MergeCartActionResponder;

class MergeCartAction extends BaseController
{
    private MergeCartUseCase $mergeCartUseCase;
    private GetCartUseCase $getCartUseCase;
    private MergeCartActionResponder $responder;

    public function __construct(
        MergeCartUseCase $mergeCartUseCase,
        GetCartUseCase $getCartUseCase,
        MergeCartActionResponder $responder
    ) {
        $this->mergeCartUseCase = $mergeCartUseCase;
        $this->getCartUseCase = $getCartUseCase;
        $this->responder = $responder;
    }

    public function __invoke(MergeCartRequest $request): MergeCartActionResource
    {
        $userId = (int) auth()->user()->id;
        $deviceId = $request->validated('device_id');

        $this->mergeCartUseCase->__invoke($deviceId, $userId);

        $cart = $this->getCartUseCase->__invoke($userId, null);

        return $this->responder->__invoke($cart);
    }
}
