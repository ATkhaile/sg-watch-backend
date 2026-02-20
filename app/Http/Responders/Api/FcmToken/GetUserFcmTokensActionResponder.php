<?php

namespace App\Http\Responders\Api\FcmToken;

use App\Domain\FcmToken\Entity\FcmTokenListEntity;
use App\Http\Resources\Api\FcmToken\GetUserFcmTokensActionResource;

final class GetUserFcmTokensActionResponder
{
    public function __invoke(FcmTokenListEntity $entity): GetUserFcmTokensActionResource
    {
        $resource = $this->makeResource($entity);
        return new GetUserFcmTokensActionResource($resource);
    }

    private function makeResource(FcmTokenListEntity $entity): array
    {
        return [
            'status_code' => $entity->statusCode,
            'data' => [
                'fcm_tokens' => $entity->fcmTokens,
            ],
        ];
    }
}

