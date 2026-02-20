<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StripePriceEntity;
use App\Http\Resources\Api\StripeAccount\GetStripePricesActionResource;

final class GetStripePricesActionResponder
{
    public function __invoke(StripePriceEntity $stripePriceEntity): GetStripePricesActionResource
    {
        $resource = $this->makeResource($stripePriceEntity);
        return new GetStripePricesActionResource($resource);
    }

    public function makeResource(StripePriceEntity $stripePriceEntity)
    {
        return [
            'stripe_prices' => $stripePriceEntity->jsonSerialize(),
            'status_code' => $stripePriceEntity->getStatus(),
        ];
    }
}
