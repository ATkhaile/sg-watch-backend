<?php

namespace App\Http\Actions\Api\ShopCart;

use App\Domain\ShopCart\UseCase\UpdateCartItemUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCart\UpdateCartItemRequest;
use App\Http\Resources\Api\ShopCart\UpdateCartItemActionResource;
use App\Http\Responders\Api\ShopCart\UpdateCartItemActionResponder;

class UpdateCartItemAction extends BaseController
{
    private UpdateCartItemUseCase $useCase;
    private UpdateCartItemActionResponder $responder;

    public function __construct(
        UpdateCartItemUseCase $useCase,
        UpdateCartItemActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateCartItemRequest $request, int $itemId): UpdateCartItemActionResource
    {
        $user = auth()->user();
        $userId = $user ? (int) $user->id : null;
        $deviceId = $request->validated('device_id');

        $result = $this->useCase->__invoke(
            $userId,
            $deviceId,
            $itemId,
            (int) $request->validated('quantity'),
        );

        return $this->responder->__invoke($result);
    }
}
