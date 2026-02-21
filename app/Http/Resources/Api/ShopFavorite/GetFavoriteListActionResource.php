<?php

namespace App\Http\Resources\Api\ShopFavorite;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFavoriteListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Favorites retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'favorites' => $this->resource['favorites'],
            ],
        ];
    }
}
