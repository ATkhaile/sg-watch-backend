<?php

namespace App\Domain\Stripe\Factory;

use App\Domain\Stripe\Entity\CreateCustomerRequestEntity;
use Illuminate\Http\Request;

class CreateCustomerRequestFactory
{
    public function createFromRequest(Request $request): CreateCustomerRequestEntity
    {
        return new CreateCustomerRequestEntity(
            payload: $request->getContent(),
            signature: $request->header('Stripe-Signature') ?? '',
            endpointSecret: ''
        );
    }
}
