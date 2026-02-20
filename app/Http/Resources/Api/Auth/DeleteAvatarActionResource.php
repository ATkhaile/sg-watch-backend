<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class DeleteAvatarActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Avatar deleted successfully',
            'status_code' => 200,
            'data' => [
                'success' => true
            ]
        ];
    }
}
