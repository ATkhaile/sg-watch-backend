<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\GetBigSaleListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\BigSale\GetBigSaleListRequest;
use App\Http\Resources\Api\BigSale\GetBigSaleListActionResource;
use App\Http\Responders\Api\BigSale\GetBigSaleListActionResponder;

class GetBigSaleListAction extends BaseController
{
    private GetBigSaleListUseCase $useCase;
    private GetBigSaleListActionResponder $responder;

    public function __construct(
        GetBigSaleListUseCase $useCase,
        GetBigSaleListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetBigSaleListRequest $request): GetBigSaleListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
