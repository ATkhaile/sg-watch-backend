<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminGetOrderDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopOrder\AdminGetOrderDetailActionResource;
use App\Http\Responders\Api\ShopOrder\AdminGetOrderDetailActionResponder;
use Illuminate\Http\JsonResponse;

class AdminGetOrderDetailAction extends BaseController
{
    private AdminGetOrderDetailUseCase $useCase;
    private AdminGetOrderDetailActionResponder $responder;

    public function __construct(
        AdminGetOrderDetailUseCase $useCase,
        AdminGetOrderDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): AdminGetOrderDetailActionResource|JsonResponse
    {
        $order = $this->useCase->__invoke($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return $this->responder->__invoke($order);
    }
}
