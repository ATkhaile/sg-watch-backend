<?php

namespace App\Http\Resources\Api\ShopCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class GetCollectionsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Collections retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'collections' => $this->resource['collections'],
            ],
        ];
    }
}
