<?php

namespace App\Http\Responders\Api\ShopReview;

use App\Http\Resources\Api\ShopReview\GetProductReviewsActionResource;

final class GetProductReviewsActionResponder
{
    public function __invoke(array $result): GetProductReviewsActionResource
    {
        return new GetProductReviewsActionResource($result);
    }
}
