<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetStripeTransactionsUseCase;
use App\Domain\StripeAccount\Factory\GetStripeTransactionsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetStripeTransactionsRequest;
use App\Http\Resources\Api\StripeAccount\GetStripeTransactionsActionResource;
use App\Http\Responders\Api\StripeAccount\GetStripeTransactionsActionResponder;

class GetStripeTransactionsAction extends BaseController
{
    public function __construct(
        private GetStripeTransactionsUseCase $getStripeTransactionsUseCase,
        private GetStripeTransactionsRequestFactory $factory,
        private GetStripeTransactionsActionResponder $responder
    ) {}

    public function __invoke(GetStripeTransactionsRequest $request): GetStripeTransactionsActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $stripeTransactions = $this->getStripeTransactionsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripeTransactions);
    }
}
