<?php

namespace App\Http\Responders\Api\Auth;

use App\Http\Resources\Api\Auth\UpdateAvatarActionResource;

final class UpdateAvatarActionResponder
{
    public function __invoke(array $data): UpdateAvatarActionResource
    {
        return new UpdateAvatarActionResource([
            'avatar_data' => $data
        ]);
    }
}
