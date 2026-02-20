<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StripeProductEntity;
use App\Http\Resources\Api\StripeAccount\GetStripeProductsActionResource;

final class GetStripeProductsActionResponder
{
    public function __invoke(StripeProductEntity $stripeProductEntity): GetStripeProductsActionResource
    {
        $resource = $this->makeResource($stripeProductEntity);
        return new GetStripeProductsActionResource($resource);
    }

    public function makeResource(StripeProductEntity $stripeProductEntity)
    {
        return [
            'stripe_products' => $stripeProductEntity->jsonSerialize(),
            'status_code' => $stripeProductEntity->getStatus(),
        ];
    }
}
