<?php

namespace App\Http\Actions\Api\ShopCart;

use App\Domain\ShopCart\UseCase\AddToCartUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCart\AddToCartRequest;
use App\Http\Resources\Api\ShopCart\AddToCartActionResource;
use App\Http\Responders\Api\ShopCart\AddToCartActionResponder;

class AddToCartAction extends BaseController
{
    private AddToCartUseCase $useCase;
    private AddToCartActionResponder $responder;

    public function __construct(
        AddToCartUseCase $useCase,
        AddToCartActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AddToCartRequest $request): AddToCartActionResource
    {
        $user = auth()->user();
        $userId = $user ? (int) $user->id : null;
        $deviceId = $request->validated('device_id');

        $result = $this->useCase->__invoke(
            $userId,
            $deviceId,
            (int) $request->validated('product_id'),
            (int) ($request->validated('quantity') ?? 1),
            $request->validated('currency') ?? 'JPY',
        );

        return $this->responder->__invoke($result);
    }
}
