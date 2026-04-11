<?php

namespace App\Domain\Firebase\Factory;

use App\Domain\Firebase\Entity\FirebaseNotificationEntity;
use App\Http\Requests\Api\Firebase\GetFirebaseNotificationsRequest;

class GetFirebaseNotificationsRequestFactory
{
    public function createFromRequest(GetFirebaseNotificationsRequest $request): FirebaseNotificationEntity
    {
        $isRead = $request->input('is_read');

        return new FirebaseNotificationEntity(
            page: $request->input('page', 1),
            limit: $request->input('limit', 20),
            fcm_token: $request->input('fcm_token'),
            is_read: $isRead !== null ? filter_var($isRead, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
        );
    }
}
