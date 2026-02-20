<?php
namespace App\Domain\FcmToken\Factory;

use App\Domain\FcmToken\Entity\UpdateFcmTokenStatusRequestEntity;
use App\Http\Requests\Api\FcmToken\UpdateFcmTokenStatusRequest;

class UpdateFcmTokenStatusRequestFactory
{
    public function createFromRequest(UpdateFcmTokenStatusRequest $request): UpdateFcmTokenStatusRequestEntity
    {
        return new UpdateFcmTokenStatusRequestEntity(
            fcmTokenId: (int) $request->route('fcm_token_id'),
            activeStatus: $request->input('active_status'),
        );
    }
}