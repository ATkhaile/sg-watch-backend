<?php

namespace App\Domain\FcmToken\Factory;

use App\Domain\FcmToken\Entity\CreateFcmTokenRequestEntity;
use App\Http\Requests\Api\FcmToken\CreateFcmTokenRequest;

class CreateFcmTokenRequestFactory
{
    public function createFromRequest(CreateFcmTokenRequest $request): CreateFcmTokenRequestEntity
    {
        $userId = auth()->user()->id;
        return new CreateFcmTokenRequestEntity(
            fcm_token: $request->input('fcm_token'),
            user_id: $userId,
            device_name: $request->input('device_name'),
            app_version_name: $request->input('app_version_name'),
            app_id: $request->header('X-App-Id'),
        );
    }
}
