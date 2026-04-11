<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\GetPostListActionResource;

final class GetPostListActionResponder
{
    public function __invoke(array $result): GetPostListActionResource
    {
        return new GetPostListActionResource($result);
    }
}
