<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Domain\ShopProduct\UseCase\GetProductListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProduct\GetProductListRequest;
use App\Http\Resources\Api\ShopProduct\GetProductListActionResource;
use App\Http\Responders\Api\ShopProduct\GetProductListActionResponder;

class GetProductListAction extends BaseController
{
    private GetProductListUseCase $useCase;
    private GetProductListActionResponder $responder;

    public function __construct(
        GetProductListUseCase $useCase,
        GetProductListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetProductListRequest $request): GetProductListActionResource
    {
        $filters = $request->validated();
        $user = auth()->user();
        $userId = $user ? (int) $user->id : null;

        $result = $this->useCase->__invoke($filters, $userId);

        return $this->responder->__invoke($result);
    }
}
