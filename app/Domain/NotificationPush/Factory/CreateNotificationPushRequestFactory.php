<?php

namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\CreateNotificationPushRequestEntity;
use App\Http\Requests\Api\NotificationPush\CreateNotificationPushRequest;

class CreateNotificationPushRequestFactory
{
    public function createFromRequest(CreateNotificationPushRequest $request): CreateNotificationPushRequestEntity
    {
        return new CreateNotificationPushRequestEntity(
            title: $request->input('title'),
            message: $request->input('message'),
            img_path: $request->file('img_path'),
            all_user_flag: (bool) $request->boolean('all_user_flag'),
            push_now_flag: (bool) $request->boolean('push_now_flag'),
            push_schedule: $request->input('push_schedule'),
            user_ids: $request->input('user_ids', []),
            sound: $request->input('sound'),
            redirect_type: $request->input('redirect_type'),
            app_page_id: $request->input('app_page_id'),
            attach_file: $request->file('attach_file'),
            attach_link: $request->input('attach_link'),
        );
    }
}
