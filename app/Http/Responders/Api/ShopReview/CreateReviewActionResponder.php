<?php

namespace App\Http\Responders\Api\ShopReview;

use App\Http\Resources\Api\ShopReview\CreateReviewActionResource;

final class CreateReviewActionResponder
{
    public function __invoke(array $result): CreateReviewActionResource
    {
        return new CreateReviewActionResource($result);
    }
}
