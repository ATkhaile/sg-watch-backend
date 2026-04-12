<?php

namespace App\Http\Actions\Api\ShopInventoryHistory;

use App\Domain\ShopInventoryHistory\UseCase\GetInventoryHistoryUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopInventoryHistory\GetInventoryHistoryRequest;
use App\Http\Resources\Api\ShopInventoryHistory\GetInventoryHistoryActionResource;
use App\Http\Responders\Api\ShopInventoryHistory\GetInventoryHistoryActionResponder;

class GetInventoryHistoryAction extends BaseController
{
    private GetInventoryHistoryUseCase $useCase;
    private GetInventoryHistoryActionResponder $responder;

    public function __construct(
        GetInventoryHistoryUseCase $useCase,
        GetInventoryHistoryActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetInventoryHistoryRequest $request): GetInventoryHistoryActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
