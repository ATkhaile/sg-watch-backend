<?php

namespace App\Http\Actions\Api\ShopCart;

use App\Domain\ShopCart\UseCase\RemoveCartItemUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCart\RemoveCartItemRequest;
use App\Http\Resources\Api\ShopCart\RemoveCartItemActionResource;
use App\Http\Responders\Api\ShopCart\RemoveCartItemActionResponder;

class RemoveCartItemAction extends BaseController
{
    private RemoveCartItemUseCase $useCase;
    private RemoveCartItemActionResponder $responder;

    public function __construct(
        RemoveCartItemUseCase $useCase,
        RemoveCartItemActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(RemoveCartItemRequest $request, int $itemId): RemoveCartItemActionResource
    {
        $user = auth()->user();
        $userId = $user ? (int) $user->id : null;
        $deviceId = $request->validated('device_id');

        $result = $this->useCase->__invoke(
            $userId,
            $deviceId,
            $itemId,
        );

        return $this->responder->__invoke($result);
    }
}
