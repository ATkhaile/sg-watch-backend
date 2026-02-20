<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StripePaymentLinkEntity;
use App\Http\Resources\Api\StripeAccount\GetStripePaymentLinksActionResource;

final class GetStripePaymentLinksActionResponder
{
    public function __invoke(StripePaymentLinkEntity $stripePaymentLinkEntity): GetStripePaymentLinksActionResource
    {
        $resource = $this->makeResource($stripePaymentLinkEntity);
        return new GetStripePaymentLinksActionResource($resource);
    }

    public function makeResource(StripePaymentLinkEntity $stripePaymentLinkEntity)
    {
        return [
            'stripe_payment_links' => $stripePaymentLinkEntity->jsonSerialize(),
            'status_code' => $stripePaymentLinkEntity->getStatus(),
        ];
    }
}
