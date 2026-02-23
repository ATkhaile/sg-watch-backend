<?php

namespace App\Http\Responders\Api\ShopReview;

use App\Http\Resources\Api\ShopReview\UpdateReviewActionResource;

final class UpdateReviewActionResponder
{
    public function __invoke(array $result): UpdateReviewActionResource
    {
        return new UpdateReviewActionResource($result);
    }
}
