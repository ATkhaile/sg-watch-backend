<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetAllStripeAccountUseCase;
use App\Domain\StripeAccount\Factory\GetAllStripeAccountRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetAllStripeAccountRequest;
use App\Http\Resources\Api\StripeAccount\GetAllStripeAccountActionResource;
use App\Http\Responders\Api\StripeAccount\GetAllStripeAccountActionResponder;

class GetAllStripeAccountAction extends BaseController
{
    public function __construct(
        private GetAllStripeAccountUseCase $getAllStripeAccountUseCase,
        private GetAllStripeAccountRequestFactory $factory,
        private GetAllStripeAccountActionResponder $responder
    ) {}

    public function __invoke(GetAllStripeAccountRequest $request): GetAllStripeAccountActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripeAccounts = $this->getAllStripeAccountUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripeAccounts);
    }
}
