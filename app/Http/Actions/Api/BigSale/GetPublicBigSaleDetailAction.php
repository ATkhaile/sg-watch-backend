<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\GetPublicBigSaleDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\BigSale\GetPublicBigSaleDetailActionResource;
use App\Http\Responders\Api\BigSale\GetPublicBigSaleDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetPublicBigSaleDetailAction extends BaseController
{
    private GetPublicBigSaleDetailUseCase $useCase;
    private GetPublicBigSaleDetailActionResponder $responder;

    public function __construct(
        GetPublicBigSaleDetailUseCase $useCase,
        GetPublicBigSaleDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetPublicBigSaleDetailActionResource|JsonResponse
    {
        $bigSale = $this->useCase->__invoke($id);
        if (!$bigSale) {
            return response()->json([
                'message' => 'Big sale not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($bigSale);
    }
}
