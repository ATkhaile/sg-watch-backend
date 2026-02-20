<?php

namespace App\Http\Resources\Api\Stripe;

use Illuminate\Http\Resources\Json\JsonResource;

class PortalLinkResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'portal_link' => $this->resource['portal_link'],
            'status' => $this->resource['status'],
        ];
    }
}
