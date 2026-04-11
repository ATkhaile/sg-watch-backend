<?php

namespace App\Http\Responders\Api\Post;

use App\Http\Resources\Api\Post\DeletePostActionResource;

final class DeletePostActionResponder
{
    public function __invoke(array $result): DeletePostActionResource
    {
        return new DeletePostActionResource($result);
    }
}
