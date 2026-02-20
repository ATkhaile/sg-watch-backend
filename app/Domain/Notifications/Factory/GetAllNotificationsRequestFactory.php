<?php

namespace App\Domain\Notifications\Factory;

use App\Domain\Notifications\Entity\NotificationEntity;
use App\Http\Requests\Api\Notifications\GetAllNotificationsRequest;

class GetAllNotificationsRequestFactory
{
    public function createFromRequest(GetAllNotificationsRequest $request): NotificationEntity
    {
        return new NotificationEntity(
            page: $request->input('page', 1),
            limit: $request->input('limit', 20),
            search: $request->input('search'),
            type: $request->input('type'),
            senderType: $request->input('sender_type'),
            direction: $request->input('direction', 'DESC'),
            sort: $request->input('sort', 'updated_at'),
        );
    }
}
