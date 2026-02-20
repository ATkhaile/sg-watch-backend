<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetStripeProductsUseCase;
use App\Domain\StripeAccount\Factory\GetStripeProductsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetStripeProductsRequest;
use App\Http\Resources\Api\StripeAccount\GetStripeProductsActionResource;
use App\Http\Responders\Api\StripeAccount\GetStripeProductsActionResponder;

class GetStripeProductsAction extends BaseController
{
    public function __construct(
        private GetStripeProductsUseCase $getStripeProductsUseCase,
        private GetStripeProductsRequestFactory $factory,
        private GetStripeProductsActionResponder $responder
    ) {}

    public function __invoke(GetStripeProductsRequest $request): GetStripeProductsActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripeProducts = $this->getStripeProductsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripeProducts);
    }
}
