<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetAllStripeAccountFromGuestUseCase;
use App\Domain\StripeAccount\Factory\GetAllStripeAccountFromGuestRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetAllStripeAccountFromGuestRequest;
use App\Http\Resources\Api\StripeAccount\GetAllStripeAccountActionResource;
use App\Http\Responders\Api\StripeAccount\GetAllStripeAccountActionResponder;

class GetAllStripeAccountFromGuestAction extends BaseController
{
    public function __construct(
        private GetAllStripeAccountFromGuestUseCase $getAllStripeAccountFromGuestUseCase,
        private GetAllStripeAccountFromGuestRequestFactory $factory,
        private GetAllStripeAccountActionResponder $responder
    ) {}

    public function __invoke(GetAllStripeAccountFromGuestRequest $request): GetAllStripeAccountActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripeAccounts = $this->getAllStripeAccountFromGuestUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripeAccounts);
    }
}
