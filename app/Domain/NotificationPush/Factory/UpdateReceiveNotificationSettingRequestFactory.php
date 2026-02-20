<?php

namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\UpdateReceiveNotificationSettingRequestEntity;
use App\Http\Requests\Api\NotificationPush\UpdateReceiveNotificationRequest;

class UpdateReceiveNotificationSettingRequestFactory
{
    public function createFromRequest(UpdateReceiveNotificationRequest $request): UpdateReceiveNotificationSettingRequestEntity
    {
        return new UpdateReceiveNotificationSettingRequestEntity(
            fcmToken: $request->input('fcm_token'),
            receiveNotificationChat: $request->has('receive_notification_chat')? $request->boolean('receive_notification_chat'): null,
            receiveReservation: $request->has('receive_reservation')? $request->boolean('receive_reservation'): null,
        );
    }
}
