<?php

namespace App\Http\Resources\Api\UserProfile;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status_code' => $this->resource['status_code'],
            'message' => $this->resource['message'],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode($this->resource['status_code']);
    }
}
