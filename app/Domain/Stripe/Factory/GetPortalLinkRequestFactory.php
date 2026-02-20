<?php

namespace App\Domain\Stripe\Factory;

use App\Domain\Stripe\Entity\GetPortalLinkRequestEntity;

class GetPortalLinkRequestFactory
{
    public function createFromAuth(): GetPortalLinkRequestEntity
    {
        $user = auth()->user() ?? null;

        return new GetPortalLinkRequestEntity(
            userEmail: $user->email
        );
    }
}
