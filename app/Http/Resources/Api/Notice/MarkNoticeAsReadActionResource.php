<?php

namespace App\Http\Resources\Api\Notice;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkNoticeAsReadActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => $this->resource['status_code'],
            'data' => null,
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode($this->resource['status_code']);
    }
}
