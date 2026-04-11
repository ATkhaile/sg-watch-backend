<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\GetPostDetailActionResource;

final class GetPostDetailActionResponder
{
    public function __invoke(array $post): GetPostDetailActionResource
    {
        return new GetPostDetailActionResource(['post' => $post]);
    }
}
