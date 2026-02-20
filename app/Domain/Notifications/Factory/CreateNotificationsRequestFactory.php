<?php

namespace App\Domain\Notifications\Factory;

use App\Domain\Notifications\Entity\CreateNotificationsRequestEntity;
use App\Http\Requests\Api\Notifications\CreateNotificationsRequest;

class CreateNotificationsRequestFactory
{
    public function createFromRequest(CreateNotificationsRequest $request): CreateNotificationsRequestEntity
    {
        return new CreateNotificationsRequestEntity(
            title: $request->input('title'),
            content: $request->input('content'),
            push_type: $request->input('push_type'),
            push_datetime: $request->input('push_datetime'),
            push_now_flag: $request->input('push_now_flag'),
            file: $request->file('image_file'),
            sender_type: $request->input('sender_type'),
        );
    }
}
