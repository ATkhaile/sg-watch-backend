<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Entity\AuthEntity;
use App\Http\Resources\Api\Auth\LoginActionResource;

/**
 * 認証アクションのレスポンダー
 */
final class LoginActionResponder
{
    public function __invoke(AuthEntity|array $authEntity): LoginActionResource
    {
        if (is_array($authEntity)) {
            return new LoginActionResource($authEntity);
        }

        $resourceAry = $this->makeAuthForResource($authEntity);
        return new LoginActionResource($resourceAry);
    }

    private function makeAuthForResource(AuthEntity $authEntity): array
    {
        return [
            'token' => $authEntity->getToken(),
        ];
    }
}
