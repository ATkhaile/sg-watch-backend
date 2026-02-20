<?php

namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\UpdateNotificationPushRequestEntity;
use App\Http\Requests\Api\NotificationPush\UpdateNotificationPushRequest;

class UpdateNotificationPushRequestFactory
{
    public function createFromRequest(UpdateNotificationPushRequest $request): UpdateNotificationPushRequestEntity
    {
        return new UpdateNotificationPushRequestEntity(
            title: $request->input('title'),
            message: $request->input('message'),
            img_path: $request->file('img_path'),
            all_user_flag: (bool) $request->boolean('all_user_flag'),
            push_now_flag: (bool) $request->boolean('push_now_flag'),
            push_schedule: $request->input('push_schedule'),
            user_ids: $request->input('user_ids', []),
            remove_image: (bool) $request->boolean('remove_image'),
            sound: $request->input('sound'),
            redirect_type: $request->input('redirect_type'),
            app_page_id: $request->input('app_page_id'),
            attach_file: $request->file('attach_file'),
            attach_link: $request->input('attach_link'),
            remove_attach_file: (bool) $request->boolean('remove_attach_file'),
        );
    }
}
