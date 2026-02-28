<?php

namespace App\Http\Resources\Api\Notice;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateNoticeActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'notice' => $this->resource['notice'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
