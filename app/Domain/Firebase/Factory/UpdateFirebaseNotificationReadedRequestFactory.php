<?php

namespace App\Domain\Firebase\Factory;

use App\Domain\Firebase\Entity\UpdateFirebaseNotificationReadedEntity;
use App\Http\Requests\Api\Firebase\UpdateFirebaseNotificationReadedRequest;

class UpdateFirebaseNotificationReadedRequestFactory
{
    public function createFromRequest(UpdateFirebaseNotificationReadedRequest $request): UpdateFirebaseNotificationReadedEntity
    {
        return new UpdateFirebaseNotificationReadedEntity(
            fcmToken: $request->input('fcm_token'),
        );
    }
}
