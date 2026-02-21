<?php

namespace App\Http\Resources\Api\ShopFavorite;

use Illuminate\Http\Resources\Json\JsonResource;

class ToggleFavoriteActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $this->resource['message'],
            'status_code' => $success ? 200 : 422,
            'data' => $success ? [
                'is_favorited' => $this->resource['is_favorited'],
                'product_id' => $this->resource['product_id'],
            ] : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 200 : 422;

        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
