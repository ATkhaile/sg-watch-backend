<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\CheckoutUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\CheckoutRequest;
use App\Http\Resources\Api\ShopOrder\CheckoutActionResource;
use App\Http\Responders\Api\ShopOrder\CheckoutActionResponder;

class CheckoutAction extends BaseController
{
    private CheckoutUseCase $useCase;
    private CheckoutActionResponder $responder;

    public function __construct(
        CheckoutUseCase $useCase,
        CheckoutActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CheckoutRequest $request): CheckoutActionResource
    {
        $userId = (int) auth()->user()->id;

        $result = $this->useCase->__invoke($userId, $request->validated());

        return $this->responder->__invoke($result);
    }
}
