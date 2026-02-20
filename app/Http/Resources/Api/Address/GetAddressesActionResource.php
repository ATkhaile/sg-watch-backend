<?php

namespace App\Http\Resources\Api\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAddressesActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => __('address.get.success'),
            'status_code' => 200,
            'data' => [
                'addresses' => $this->resource['addresses'],
            ],
        ];
    }
}
