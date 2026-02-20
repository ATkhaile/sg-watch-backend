<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetStripePaymentLinksUseCase;
use App\Domain\StripeAccount\Factory\GetStripePaymentLinksRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetStripePaymentLinksRequest;
use App\Http\Resources\Api\StripeAccount\GetStripePaymentLinksActionResource;
use App\Http\Responders\Api\StripeAccount\GetStripePaymentLinksActionResponder;

class GetStripePaymentLinksAction extends BaseController
{
    public function __construct(
        private GetStripePaymentLinksUseCase $getStripePaymentLinksUseCase,
        private GetStripePaymentLinksRequestFactory $factory,
        private GetStripePaymentLinksActionResponder $responder
    ) {}

    public function __invoke(GetStripePaymentLinksRequest $request): GetStripePaymentLinksActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripePaymentLinks = $this->getStripePaymentLinksUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripePaymentLinks);
    }
}
