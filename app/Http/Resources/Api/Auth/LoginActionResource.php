<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginActionResource extends JsonResource
{
    public function toArray($request): array
    {
        if (isset($this->resource['requires_verification'])) {
            return [
                'message' => $this->resource['message'],
                'requires_verification' => $this->resource['requires_verification'],
                'status_code' => $this->resource['status_code']
            ];
        }

        return [
            'status_code' => 200,
            'token' => $this->resource['token'],
        ];
    }
}
