<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Entity\AuthEntity;
use App\Http\Resources\Api\Auth\LogoutActionResource;

/**
 * 認証アクションのレスポンダー
 */
final class LogoutActionResponder
{
    public function __invoke(AuthEntity $authEntity): LogoutActionResource
    {
        $resourceAry = $this->makeAuthForResource($authEntity);
        return new LogoutActionResource($resourceAry);
    }

    private function makeAuthForResource(AuthEntity $authEntity)
    {
        return [
            'message' => $authEntity->getMessage(),
        ];
    }
}
