<?php

namespace App\Domain\Stripe\Factory;

use App\Http\Requests\Api\Stripe\GetMemberStripeTokenRequest;
use App\Domain\Stripe\Entity\MemberStripeTokenEntity;

class GetMemberStripeTokenRequestFactory
{
    public function createFromRequest(GetMemberStripeTokenRequest $request): MemberStripeTokenEntity
    {
        return new MemberStripeTokenEntity(
            number: $request->number,
            exp_month: $request->exp_month,
            exp_year: $request->exp_year,
            cvc: $request->cvc,
        );
    }
}
