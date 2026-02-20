<?php

namespace App\Http\Resources\Api\FcmToken;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status_code' => $this->resource['status_code'],
            'message' => $this->resource['message'],
        ];
    }
}
