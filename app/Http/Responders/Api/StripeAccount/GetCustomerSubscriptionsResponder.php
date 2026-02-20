<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\CustomerSubscriptionEntity;
use App\Http\Resources\Api\StripeAccount\CustomerSubscriptionsResource;

class GetCustomerSubscriptionsResponder
{
    public function __invoke(CustomerSubscriptionEntity $entity): CustomerSubscriptionsResource
    {
        return new CustomerSubscriptionsResource($entity);
    }
}
