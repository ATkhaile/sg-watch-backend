<?php

namespace App\Domain\Notifications\Factory;

use App\Domain\Notifications\Entity\UpdateNotificationsRequestEntity;
use App\Http\Requests\Api\Notifications\UpdateNotificationsRequest;

class UpdateNotificationsRequestFactory
{
    public function createFromRequest(UpdateNotificationsRequest $request): UpdateNotificationsRequestEntity
    {
        return new UpdateNotificationsRequestEntity(
            title: $request->input('title'),
            content: $request->input('content'),
            push_type: $request->input('push_type'),
            push_datetime: $request->input('push_datetime'),
            push_now_flag: $request->input('push_now_flag'),
            file: $request->file('image_file'),
            sender_type: $request->input('sender_type')
        );
    }
}
