<?php

namespace App\Http\Actions\Api\DiscountCode;

use App\Domain\DiscountCode\UseCase\GetDiscountCodeDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\DiscountCode\GetDiscountCodeDetailActionResource;
use App\Http\Responders\Api\DiscountCode\GetDiscountCodeDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetDiscountCodeDetailAction extends BaseController
{
    private GetDiscountCodeDetailUseCase $useCase;
    private GetDiscountCodeDetailActionResponder $responder;

    public function __construct(
        GetDiscountCodeDetailUseCase $useCase,
        GetDiscountCodeDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetDiscountCodeDetailActionResource|JsonResponse
    {
        $discountCode = $this->useCase->__invoke($id);
        if (!$discountCode) {
            return response()->json([
                'message' => 'Discount code not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($discountCode);
    }
}
