<?php

namespace App\Http\Actions\Api\DiscountCode;

use App\Domain\DiscountCode\UseCase\GetDiscountCodeByCodeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\DiscountCode\GetDiscountCodeByCodeActionResource;
use App\Http\Responders\Api\DiscountCode\GetDiscountCodeByCodeActionResponder;
use Illuminate\Http\JsonResponse;

class GetDiscountCodeByCodeAction extends BaseController
{
    private GetDiscountCodeByCodeUseCase $useCase;
    private GetDiscountCodeByCodeActionResponder $responder;

    public function __construct(
        GetDiscountCodeByCodeUseCase $useCase,
        GetDiscountCodeByCodeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(string $code): GetDiscountCodeByCodeActionResource|JsonResponse
    {
        $discountCode = $this->useCase->__invoke($code);
        if (!$discountCode) {
            return response()->json([
                'message' => 'Discount code not found or not available.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($discountCode);
    }
}
