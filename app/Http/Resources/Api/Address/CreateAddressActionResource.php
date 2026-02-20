<?php

namespace App\Http\Resources\Api\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateAddressActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => __('address.create.success'),
            'status_code' => 201,
            'data' => [
                'address' => $this->resource['address'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
