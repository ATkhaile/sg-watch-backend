<?php

namespace App\Http\Resources\Api\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatePostActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'post' => $this->resource['post'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
