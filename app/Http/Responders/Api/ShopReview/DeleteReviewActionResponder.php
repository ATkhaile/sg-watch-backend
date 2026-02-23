<?php

namespace App\Http\Responders\Api\ShopReview;

use App\Http\Resources\Api\ShopReview\DeleteReviewActionResource;

final class DeleteReviewActionResponder
{
    public function __invoke(array $result): DeleteReviewActionResource
    {
        return new DeleteReviewActionResource($result);
    }
}
