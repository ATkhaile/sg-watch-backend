<?php

namespace App\Http\Resources\Api\ShopReview;

use Illuminate\Http\Resources\Json\JsonResource;

class GetReviewDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $success ? 'Review retrieved successfully.' : $this->resource['message'],
            'status_code' => $success ? 200 : 404,
            'data' => $success ? [
                'review' => $this->resource['review'],
            ] : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 200 : 404;

        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
