<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $statusCode = $this->resource['status_code'] ?? 200;
        return [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'status_code' => $statusCode,
            'message' => $this->resource['message'] ?? __('chat.action.success'),
            'data' => $this->resource['data'] ?? null,
        ];
    }

    public function withResponse($request, $response)
    {
        // Set the HTTP status code based on the resource status_code
        $statusCode = $this->resource['status_code'] ?? 200;
        
        // If it's a validation error, set proper HTTP status
        if ($statusCode === 500) {
            $statusCode = 422; // Validation error
        }
        
        $response->setStatusCode($statusCode);
    }
}
