<?php

namespace App\Http\Responders\Api\Notifications;

use App\Domain\Notifications\Entity\NotificationDetailEntity;
use App\Http\Resources\Api\Notifications\GetNotificationsDetailActionResource;

final class GetNotificationsDetailActionResponder
{
    public function __invoke(NotificationDetailEntity $notificationEntity): GetNotificationsDetailActionResource
    {
        $resource = $this->makeResource($notificationEntity);
        return new GetNotificationsDetailActionResource($resource);
    }

    private function makeResource(NotificationDetailEntity $notificationEntity)
    {
        return [
            'notification' => $notificationEntity->jsonSerialize(),
            'status_code' => $notificationEntity->getStatusCode(),
        ];
    }
}
