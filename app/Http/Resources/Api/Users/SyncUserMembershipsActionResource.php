<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class SyncUserMembershipsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $statusCode = (int) $this->resource['status_code'];
        return [
            'success' => $this->resource['success'],
            'message' => $this->resource['message'],
            'status_code' => $statusCode,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode((int) $this->resource['status_code']);
    }
}
