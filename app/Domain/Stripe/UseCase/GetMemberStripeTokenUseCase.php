<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Entity\MemberStripeTokenEntity;
use App\Components\StripeComponent;
use App\Enums\StatusCode;
use App\Models\Location;

final class GetMemberStripeTokenUseCase
{
    public function __invoke(?int $locationId = null): MemberStripeTokenEntity
    {
        $stripeAccountId = null;

        // Get stripe_account_id from location if provided
        if ($locationId) {
            $location = Location::find($locationId);
            if ($location && $location->stripe_account_id) {
                $stripeAccountId = $location->stripe_account_id;
            }
        }

        // Get public key from stripe_accounts table
        $publicKey = StripeComponent::getPublicKey($stripeAccountId);

        if (!$publicKey) {
            throw new \Exception('Failed to get Stripe public key', StatusCode::INTERNAL_ERR);
        }

        // Get test token for development (optional)
        $token = StripeComponent::getTokenCard($stripeAccountId);

        return new MemberStripeTokenEntity(
            token: $token ?: null,
            statusCode: StatusCode::OK,
            public_key: $publicKey,
        );
    }
}
