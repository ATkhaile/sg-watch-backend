<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StripeAccountDetailEntity;
use App\Http\Resources\Api\StripeAccount\GetStripeAccountDetailActionResource;

final class GetStripeAccountDetailActionResponder
{
    public function __invoke(StripeAccountDetailEntity $stripeAccountsEntity): GetStripeAccountDetailActionResource
    {
        $resource = $this->makeResource($stripeAccountsEntity);
        return new GetStripeAccountDetailActionResource($resource);
    }

    public function makeResource(StripeAccountDetailEntity $stripeAccountsEntity)
    {
        return [
            'stripe_account' => $stripeAccountsEntity->jsonSerialize(),
            'status_code' => $stripeAccountsEntity->getStatus(),
        ];
    }
}
