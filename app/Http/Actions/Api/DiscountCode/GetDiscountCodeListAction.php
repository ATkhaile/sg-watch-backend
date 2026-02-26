<?php

namespace App\Http\Actions\Api\DiscountCode;

use App\Domain\DiscountCode\UseCase\GetDiscountCodeListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\DiscountCode\GetDiscountCodeListRequest;
use App\Http\Resources\Api\DiscountCode\GetDiscountCodeListActionResource;
use App\Http\Responders\Api\DiscountCode\GetDiscountCodeListActionResponder;

class GetDiscountCodeListAction extends BaseController
{
    private GetDiscountCodeListUseCase $useCase;
    private GetDiscountCodeListActionResponder $responder;

    public function __construct(
        GetDiscountCodeListUseCase $useCase,
        GetDiscountCodeListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetDiscountCodeListRequest $request): GetDiscountCodeListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
