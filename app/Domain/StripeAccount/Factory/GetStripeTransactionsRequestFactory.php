<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\GetStripeTransactionsRequestEntity;
use App\Http\Requests\Api\StripeAccount\GetStripeTransactionsRequest;

class GetStripeTransactionsRequestFactory
{
    public function createFromRequest(GetStripeTransactionsRequest $request): GetStripeTransactionsRequestEntity
    {
        return new GetStripeTransactionsRequestEntity(
            stripeAccountId: $request->route('stripe_account_id'),
            limit: $request->input('limit'),
            startingAfter: $request->input('starting_after'),
            endingBefore: $request->input('ending_before'),
            created: $request->input('created'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date')
        );
    }
}
