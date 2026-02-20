<?php

namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\NotificationPushEntity;
use App\Http\Requests\Api\NotificationPush\GetAllNotificationPushsRequest;

class GetAllNotificationPushsRequestFactory
{
    public function createFromRequest(GetAllNotificationPushsRequest $request): NotificationPushEntity
    {
        return new NotificationPushEntity(
            page: $request->input('page', 1),
            limit: $request->input('limit', 10),
            direction: $request->input('direction', 'DESC'),
            sort: $request->input('sort', 'updated_at'),
            search: $request->input('search'),
        );
    }
}
