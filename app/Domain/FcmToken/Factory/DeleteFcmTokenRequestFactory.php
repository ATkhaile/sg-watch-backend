<?php

namespace App\Domain\FcmToken\Factory;

use App\Domain\FcmToken\Entity\DeleteFcmTokenRequestEntity;
use App\Http\Requests\Api\FcmToken\DeleteFcmTokenRequest;

class DeleteFcmTokenRequestFactory
{
    public function createFromRequest(DeleteFcmTokenRequest $request): DeleteFcmTokenRequestEntity
    {
        return new DeleteFcmTokenRequestEntity(
            fcm_token: $request->input('fcm_token'),
        );
    }
}
