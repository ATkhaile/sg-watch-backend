<?php

namespace App\Http\Responders\Api\Notifications;

use App\Domain\Notifications\Entity\NotificationEntity;
use App\Http\Resources\Api\Notifications\GetAllNotificationsActionResource;

final class GetAllNotificationsActionResponder
{
    public function __invoke(NotificationEntity $notificationEntity): GetAllNotificationsActionResource
    {
        $resource = $this->makeResource($notificationEntity);
        return new GetAllNotificationsActionResource($resource);
    }

    private function makeResource(NotificationEntity $notificationEntity): array
    {
        return [
            'status_code' => $notificationEntity->getStatusCode(),
            'data' => [
                'notifications' => $notificationEntity->getNotifications(),
                'pagination' => $notificationEntity->getPagination(),
            ],
        ];
    }
}
