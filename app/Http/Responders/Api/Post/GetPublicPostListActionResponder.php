<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\GetPublicPostListActionResource;

final class GetPublicPostListActionResponder
{
    public function __invoke(array $result): GetPublicPostListActionResource
    {
        return new GetPublicPostListActionResource($result);
    }
}
