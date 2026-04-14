<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\RetryStripePaymentUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopOrder\RetryStripePaymentResource;
use Illuminate\Http\Request;

class RetryStripePaymentAction extends BaseController
{
    private RetryStripePaymentUseCase $useCase;

    public function __construct(RetryStripePaymentUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke(Request $request, int $id): RetryStripePaymentResource
    {
        $userId = (int) auth()->user()->id;

        $result = $this->useCase->__invoke($userId, $id);

        return new RetryStripePaymentResource($result);
    }
}
