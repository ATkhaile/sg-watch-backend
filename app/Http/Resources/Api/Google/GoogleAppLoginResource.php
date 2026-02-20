<?php

namespace App\Http\Resources\Api\Google;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoogleAppLoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status_code' => $this->resource['status_code'],
            'token' => $this->resource['token'],
            'message' => $this->resource['message'],
        ];
    }
}