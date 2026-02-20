<?php

namespace App\Http\Responders\Api\Stripe;

use App\Domain\Stripe\Entity\PortalLinkResponseEntity;
use App\Http\Resources\Api\Stripe\PortalLinkResource;

final class PortalLinkResponder
{
    public function __invoke(PortalLinkResponseEntity $responseEntity): PortalLinkResource
    {
        $resourceAry = $this->makeResource($responseEntity);
        return new PortalLinkResource($resourceAry);
    }

    private function makeResource(PortalLinkResponseEntity $responseEntity): array
    {
        return [
            'portal_link' => $responseEntity->portalLink,
            'status' => $responseEntity->statusCode->value,
        ];
    }
}
