<?php

namespace App\Http\Resources\Api\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateAddressActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => __('address.update.success'),
            'status_code' => 200,
            'data' => [
                'address' => $this->resource['address'],
            ],
        ];
    }
}
