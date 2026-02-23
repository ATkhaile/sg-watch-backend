<?php

namespace App\Http\Resources\Api\ShopReview;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProductReviewsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Product reviews retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'reviews' => $this->resource['reviews'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
