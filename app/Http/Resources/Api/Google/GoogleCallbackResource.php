<?php

namespace App\Http\Resources\Api\Google;

use Illuminate\Http\Resources\Json\JsonResource;

class GoogleCallbackResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status_code' => $this->resource['status_code'],
            'token' => $this->resource['token'],
            'message' => $this->resource['message'],
            'is_first_login' => $this->resource['is_first_login'],
        ];
    }
}
