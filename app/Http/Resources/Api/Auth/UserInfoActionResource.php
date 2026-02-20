<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = $this->resource['user_info'];
        return [
            'message' => 'Success',
            'status_code' => 200,
            'data' => [
                'user' => $user->jsonSerialize()
            ]
        ];
    }
}
