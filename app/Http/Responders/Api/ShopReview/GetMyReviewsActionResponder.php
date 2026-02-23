<?php

namespace App\Http\Responders\Api\ShopReview;

use App\Http\Resources\Api\ShopReview\GetMyReviewsActionResource;

final class GetMyReviewsActionResponder
{
    public function __invoke(array $result): GetMyReviewsActionResource
    {
        return new GetMyReviewsActionResource($result);
    }
}
