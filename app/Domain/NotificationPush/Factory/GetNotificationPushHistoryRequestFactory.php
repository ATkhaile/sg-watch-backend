<?php
namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\NotificationPushHistoryEntity;
use App\Http\Requests\Api\NotificationPush\GetNotificationPushHistoryRequest;

class GetNotificationPushHistoryRequestFactory
{
    public function createFromRequest(GetNotificationPushHistoryRequest $request): NotificationPushHistoryEntity
    {
        return new NotificationPushHistoryEntity(
            page: $request->input('page', 1),
            limit: $request->input('limit', 10),
        );
    }
}