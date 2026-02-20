<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\GetStripePaymentLinksRequestEntity;
use App\Http\Requests\Api\StripeAccount\GetStripePaymentLinksRequest;

class GetStripePaymentLinksRequestFactory
{
    public function createFromRequest(GetStripePaymentLinksRequest $request): GetStripePaymentLinksRequestEntity
    {
        return new GetStripePaymentLinksRequestEntity(
            stripeAccountId: $request->route('stripe_account_id'),
            limit: $request->input('limit'),
            startingAfter: $request->input('starting_after'),
            endingBefore: $request->input('ending_before'),
            active: $request->input('active')
        );
    }
}
