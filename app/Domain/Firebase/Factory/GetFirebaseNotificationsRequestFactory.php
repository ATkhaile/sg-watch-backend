<?php

namespace App\Domain\Firebase\Factory;

use App\Domain\Firebase\Entity\FirebaseNotificationEntity;
use App\Http\Requests\Api\Firebase\GetFirebaseNotificationsRequest;

class GetFirebaseNotificationsRequestFactory
{
    public function createFromRequest(GetFirebaseNotificationsRequest $request): FirebaseNotificationEntity
    {
        return new FirebaseNotificationEntity(
            page: $request->input('page', 1),
            limit: $request->input('limit', 20),
            fcm_token: $request->input('fcm_token'),
        );
    }
}
