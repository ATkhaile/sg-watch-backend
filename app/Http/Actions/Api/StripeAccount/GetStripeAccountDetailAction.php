<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetStripeAccountDetailUseCase;
use App\Domain\StripeAccount\Factory\GetStripeAccountDetailRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\StripeAccount\GetStripeAccountDetailActionResource;
use App\Http\Responders\Api\StripeAccount\GetStripeAccountDetailActionResponder;
use App\Http\Requests\Api\StripeAccount\FindStripeAccountRequest;

class GetStripeAccountDetailAction extends BaseController
{
    public function __construct(
        private GetStripeAccountDetailUseCase $getStripeAccountDetailUseCase,
        private GetStripeAccountDetailRequestFactory $factory,
        private GetStripeAccountDetailActionResponder $responder
    ) {}

    public function __invoke(FindStripeAccountRequest $request, int $id): GetStripeAccountDetailActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripeAccounts = $this->getStripeAccountDetailUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripeAccounts);
    }
}
