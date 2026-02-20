<?php

namespace App\Http\Responders\Api\UserProfile;

use App\Domain\UserProfile\Entity\UserProfileEntity;
use App\Http\Resources\Api\UserProfile\GetUserProfileActionResource;

final class GetUserProfileActionResponder
{
    public function __invoke(UserProfileEntity $userProfileEntity): GetUserProfileActionResource
    {
        $resource = $this->makeResource($userProfileEntity);
        return new GetUserProfileActionResource($resource);
    }

    private function makeResource(UserProfileEntity $userProfileEntity): array
    {
        return [
            'status_code' => $userProfileEntity->statusCode,
            'data' => [
                'profile' => $userProfileEntity->profile,
            ],
        ];
    }
}
