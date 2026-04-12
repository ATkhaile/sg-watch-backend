<?php

namespace App\Http\Resources\Api\ShopCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminGetCollectionDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        if (!$this->resource) {
            return [
                'message'     => 'Collection not found.',
                'status_code' => 404,
                'data'        => null,
            ];
        }

        return [
            'message'     => 'Collection retrieved successfully.',
            'status_code' => 200,
            'data'        => [
                'collection' => $this->resource,
            ],
        ];
    }
}
