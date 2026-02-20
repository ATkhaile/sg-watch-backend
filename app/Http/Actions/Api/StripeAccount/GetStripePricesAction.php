<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetStripePricesUseCase;
use App\Domain\StripeAccount\Factory\GetStripePricesRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetStripePricesRequest;
use App\Http\Resources\Api\StripeAccount\GetStripePricesActionResource;
use App\Http\Responders\Api\StripeAccount\GetStripePricesActionResponder;

class GetStripePricesAction extends BaseController
{
    public function __construct(
        private GetStripePricesUseCase $getStripePricesUseCase,
        private GetStripePricesRequestFactory $factory,
        private GetStripePricesActionResponder $responder
    ) {}

    public function __invoke(GetStripePricesRequest $request): GetStripePricesActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripePrices = $this->getStripePricesUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripePrices);
    }
}
