<?php

namespace App\Http\Responders\Api\Auth;

use App\Http\Resources\Api\Auth\DeleteAvatarActionResource;

final class DeleteAvatarActionResponder
{
    public function __invoke(): DeleteAvatarActionResource
    {
        return new DeleteAvatarActionResource([
            'success' => true
        ]);
    }
}
