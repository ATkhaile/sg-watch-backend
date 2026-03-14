<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\GetBigSaleDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\BigSale\GetBigSaleDetailActionResource;
use App\Http\Responders\Api\BigSale\GetBigSaleDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetBigSaleDetailAction extends BaseController
{
    private GetBigSaleDetailUseCase $useCase;
    private GetBigSaleDetailActionResponder $responder;

    public function __construct(
        GetBigSaleDetailUseCase $useCase,
        GetBigSaleDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetBigSaleDetailActionResource|JsonResponse
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
