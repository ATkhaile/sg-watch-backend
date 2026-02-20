<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'status_code' => $this->resource['status_code'],
            'message' => $this->resource['message'],
        ];

        if (isset($this->resource['token'])) {
            $data['reset_token'] = $this->resource['token'];
        }

        return $data;
    }
}
