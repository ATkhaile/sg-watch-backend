<?php

namespace App\Http\Responders\Api\Firebase;

use App\Http\Resources\Api\Firebase\GetFirebaseNotificationDetailActionResource;

final class GetFirebaseNotificationDetailActionResponder
{
    public function __invoke(array $notification): GetFirebaseNotificationDetailActionResource
    {
        return new GetFirebaseNotificationDetailActionResource([
            'status_code' => 200,
            'data' => $notification,
        ]);
    }
}
