<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\CustomerSubscriptionEntity;
use App\Http\Requests\Api\StripeAccount\GetCustomerSubscriptionsRequest;

class GetCustomerSubscriptionsRequestFactory
{
    public function createFromRequest(GetCustomerSubscriptionsRequest $request): CustomerSubscriptionEntity
    {
        return new CustomerSubscriptionEntity(
            page: $request->input('page'),
            limit: $request->input('limit')
        );
    }
}
