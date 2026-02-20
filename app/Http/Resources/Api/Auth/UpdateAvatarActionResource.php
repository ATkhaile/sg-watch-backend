<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateAvatarActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $avatarData = $this->resource['avatar_data'];
        return [
            'message' => 'Avatar updated successfully',
            'status_code' => 200,
            'data' => [
                'avatar_url' => $avatarData['avatar_url'],
                'path' => $avatarData['path']
            ]
        ];
    }
}
