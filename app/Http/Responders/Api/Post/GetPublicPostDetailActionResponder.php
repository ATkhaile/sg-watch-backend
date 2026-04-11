<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\GetPublicPostDetailActionResource;

final class GetPublicPostDetailActionResponder
{
    public function __invoke(array $post): GetPublicPostDetailActionResource
    {
        return new GetPublicPostDetailActionResource(['post' => $post]);
    }
}
