<?php

namespace App\Http\Responders\Api\Stripe;

use App\Domain\Stripe\Entity\MemberStripeTokenEntity;
use App\Http\Resources\Api\Stripe\GetMemberStripeTokenResource;

final class GetMemberStripeTokenResponder
{
    public function __invoke(MemberStripeTokenEntity $stripeTokenEntity): GetMemberStripeTokenResource
    {
        $resource = $this->makeResource($stripeTokenEntity);
        return new GetMemberStripeTokenResource($resource);
    }

    private function makeResource(MemberStripeTokenEntity $stripeTokenEntity): array
    {
        return [
            'status_code' => $stripeTokenEntity->statusCode,
            'data' => [
                'token' => $stripeTokenEntity->token,
                'public_key' => $stripeTokenEntity->public_key,
            ],
        ];
    }
}
