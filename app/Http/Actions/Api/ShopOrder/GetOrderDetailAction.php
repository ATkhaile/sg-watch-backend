<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\GetOrderDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopOrder\GetOrderDetailActionResource;
use App\Http\Responders\Api\ShopOrder\GetOrderDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetOrderDetailAction extends BaseController
{
    private GetOrderDetailUseCase $useCase;
    private GetOrderDetailActionResponder $responder;

    public function __construct(
        GetOrderDetailUseCase $useCase,
        GetOrderDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetOrderDetailActionResource|JsonResponse
    {
        $userId = (int) auth()->user()->id;

        $order = $this->useCase->__invoke($userId, $id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
                'status_code' => 404,
            ], 404);
        }

        return $this->responder->__invoke($order);
    }
}
