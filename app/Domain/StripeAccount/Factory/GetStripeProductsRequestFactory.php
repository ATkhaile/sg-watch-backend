<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\GetStripeProductsRequestEntity;
use App\Http\Requests\Api\StripeAccount\GetStripeProductsRequest;

class GetStripeProductsRequestFactory
{
    public function createFromRequest(GetStripeProductsRequest $request): GetStripeProductsRequestEntity
    {
        return new GetStripeProductsRequestEntity(
            stripeAccountId: $request->route('stripe_account_id'),
            limit: $request->input('limit'),
            startingAfter: $request->input('starting_after'),
            endingBefore: $request->input('ending_before'),
            active: $request->input('active')
        );
    }
}
