<?php

namespace App\Http\Resources\Api\AdminUser;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateAdminUserActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'user' => $this->resource['user'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
