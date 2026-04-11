<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\UpdatePostActionResource;

final class UpdatePostActionResponder
{
    public function __invoke(array $result): UpdatePostActionResource
    {
        return new UpdatePostActionResource($result);
    }
}
