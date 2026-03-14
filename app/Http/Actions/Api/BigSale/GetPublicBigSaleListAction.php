<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\GetPublicBigSaleListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\BigSale\GetPublicBigSaleListRequest;
use App\Http\Resources\Api\BigSale\GetPublicBigSaleListActionResource;
use App\Http\Responders\Api\BigSale\GetPublicBigSaleListActionResponder;

class GetPublicBigSaleListAction extends BaseController
{
    private GetPublicBigSaleListUseCase $useCase;
    private GetPublicBigSaleListActionResponder $responder;

    public function __construct(
        GetPublicBigSaleListUseCase $useCase,
        GetPublicBigSaleListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetPublicBigSaleListRequest $request): GetPublicBigSaleListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
