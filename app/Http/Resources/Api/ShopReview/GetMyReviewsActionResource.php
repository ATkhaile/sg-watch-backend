<?php

namespace App\Http\Resources\Api\ShopReview;

use Illuminate\Http\Resources\Json\JsonResource;

class GetMyReviewsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'My reviews retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'reviews' => $this->resource['reviews'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
