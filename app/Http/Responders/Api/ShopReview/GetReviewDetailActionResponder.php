<?php

namespace App\Http\Responders\Api\ShopReview;

use App\Http\Resources\Api\ShopReview\GetReviewDetailActionResource;

final class GetReviewDetailActionResponder
{
    public function __invoke(?array $review): GetReviewDetailActionResource
    {
        if (!$review) {
            return new GetReviewDetailActionResource(['success' => false, 'message' => 'Review not found.']);
        }

        return new GetReviewDetailActionResource(['success' => true, 'review' => $review]);
    }
}
