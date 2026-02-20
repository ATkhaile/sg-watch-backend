<?php

namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\GetNotificationPushDetailRequestEntity;
use App\Http\Requests\Api\NotificationPush\GetNotificationPushDetailRequest;

class GetNotificationPushDetailRequestFactory
{
    public function createFromRequest(GetNotificationPushDetailRequest $request): GetNotificationPushDetailRequestEntity
    {
        return new GetNotificationPushDetailRequestEntity(
            id: (int) $request->route('id')
        );
    }
}
