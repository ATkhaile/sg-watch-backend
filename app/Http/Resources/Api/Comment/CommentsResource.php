<?php

namespace App\Http\Resources\Api\Comment;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status_code' => $this->resource['status_code'],
            'message' => $this->resource['message'],
            'data' => [
                'comments' => $this->resource['comments'],
                'total_comments' => $this->resource['total_comments'] ?? count($this->resource['comments']),
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
