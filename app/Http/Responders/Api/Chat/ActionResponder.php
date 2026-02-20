<?php

namespace App\Http\Responders\Api\Chat;

use App\Http\Resources\Api\Chat\ActionResource;

final class ActionResponder
{
    public function __invoke(array $status): ActionResource
    {
        return new ActionResource($status);
    }
}
