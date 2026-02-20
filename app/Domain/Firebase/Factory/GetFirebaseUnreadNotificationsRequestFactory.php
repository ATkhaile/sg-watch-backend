<?php

namespace App\Domain\Firebase\Factory;

use App\Domain\Firebase\Entity\GetFirebaseUnreadNotificationsEntity;
use App\Http\Requests\Api\Firebase\GetFirebaseUnreadNotificationsRequest;

class GetFirebaseUnreadNotificationsRequestFactory
{
    public function createFromRequest(GetFirebaseUnreadNotificationsRequest $request): GetFirebaseUnreadNotificationsEntity
    {
        return new GetFirebaseUnreadNotificationsEntity(
            fcmToken: $request->input('fcm_token'),
        );
    }
}
