<?php

namespace App\Http\Resources\Api\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class GetPublicPostListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Posts retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'posts' => $this->resource['posts'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
