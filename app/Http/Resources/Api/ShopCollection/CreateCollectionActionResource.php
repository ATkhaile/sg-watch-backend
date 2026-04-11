<?php

namespace App\Http\Resources\Api\ShopCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateCollectionActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'collection' => $this->resource['collection'],
            ],
        ];
    }
}
