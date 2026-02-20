<?php

namespace App\Http\Resources\Api\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class DeleteAddressActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => __('address.delete.success'),
            'status_code' => 200,
        ];
    }
}
