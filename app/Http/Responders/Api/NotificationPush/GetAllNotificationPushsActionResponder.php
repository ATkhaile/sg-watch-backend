<?php

namespace App\Http\Responders\Api\NotificationPush;

use App\Domain\NotificationPush\Entity\NotificationPushEntity;
use App\Http\Resources\Api\NotificationPush\GetAllNotificationPushsActionResource;

final class GetAllNotificationPushsActionResponder
{
    public function __invoke(NotificationPushEntity $entity): GetAllNotificationPushsActionResource
    {
        $resource = $this->makeResource($entity);
        return new GetAllNotificationPushsActionResource($resource);
    }

    public function makeResource(NotificationPushEntity $entity)
    {
        return [
            'status_code' => $entity->statusCode,
            'data' => [
                'notification_pushs' => $entity->notificationPushs,
                'pagination' => $entity->pagination,
            ],
        ];
    }
}
