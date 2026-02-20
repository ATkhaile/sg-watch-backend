<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StripeAccountEntity;
use App\Http\Resources\Api\StripeAccount\GetAllStripeAccountActionResource;

final class GetAllStripeAccountActionResponder
{
    public function __invoke(StripeAccountEntity $stripeAccountEntity): GetAllStripeAccountActionResource
    {
        $resource = $this->makeResource($stripeAccountEntity);
        return new GetAllStripeAccountActionResource($resource);
    }

    public function makeResource(StripeAccountEntity $stripeAccountEntity)
    {
        return [
            'status_code' => $stripeAccountEntity->getStatus(),
            'data' => [
                'stripe_account' => $stripeAccountEntity->getStripeAccount(),
                'pagination' =>  $stripeAccountEntity->getPagination(),
            ],
        ];
    }
}
