<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\CreatePostActionResource;

final class CreatePostActionResponder
{
    public function __invoke(array $result): CreatePostActionResource
    {
        return new CreatePostActionResource($result);
    }
}
