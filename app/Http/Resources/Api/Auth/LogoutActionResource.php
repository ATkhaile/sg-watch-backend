<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 認証アクションのリソースクラス
 */
class LogoutActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
        ];
    }
}
