<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\GetStripePricesRequestEntity;
use App\Http\Requests\Api\StripeAccount\GetStripePricesRequest;

class GetStripePricesRequestFactory
{
    public function createFromRequest(GetStripePricesRequest $request): GetStripePricesRequestEntity
    {
        return new GetStripePricesRequestEntity(
            stripeAccountId: $request->route('stripe_account_id'),
            productId: $request->input('product_id'),
            limit: $request->input('limit'),
            startingAfter: $request->input('starting_after'),
            endingBefore: $request->input('ending_before'),
            active: $request->input('active')
        );
    }
}
