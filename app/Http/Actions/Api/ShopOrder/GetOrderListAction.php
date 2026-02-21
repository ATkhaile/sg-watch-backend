<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\GetOrderListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\GetOrderListRequest;
use App\Http\Resources\Api\ShopOrder\GetOrderListActionResource;
use App\Http\Responders\Api\ShopOrder\GetOrderListActionResponder;

class GetOrderListAction extends BaseController
{
    private GetOrderListUseCase $useCase;
    private GetOrderListActionResponder $responder;

    public function __construct(
        GetOrderListUseCase $useCase,
        GetOrderListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetOrderListRequest $request): GetOrderListActionResource
    {
        $userId = (int) auth()->user()->id;
        $status = $request->validated('status');
        $perPage = (int) ($request->validated('per_page') ?? 15);

        $result = $this->useCase->__invoke($userId, $status, $perPage);

        return $this->responder->__invoke($result);
    }
}
