<?php

namespace App\Http\Resources\Api\ShopCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class DeleteCollectionActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message'     => $this->resource['message'],
            'status_code' => $this->resource['success'] ? 200 : 422,
            'data'        => null,
        ];
    }
}
