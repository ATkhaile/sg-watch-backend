<?php

namespace App\Http\Actions\Api\ShopCart;

use App\Domain\ShopCart\UseCase\GetCartUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCart\GetCartActionResource;
use App\Http\Responders\Api\ShopCart\GetCartActionResponder;
use Illuminate\Http\Request;

class GetCartAction extends BaseController
{
    private GetCartUseCase $useCase;
    private GetCartActionResponder $responder;

    public function __construct(
        GetCartUseCase $useCase,
        GetCartActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): GetCartActionResource
    {
        $user = auth()->user();
        $userId = $user ? (int) $user->id : null;
        $deviceId = $request->query('device_id');

        $cart = $this->useCase->__invoke($userId, $deviceId);

        return $this->responder->__invoke($cart);
    }
}
