<?php

namespace App\Http\Responders\Api\NotificationPush;

use App\Domain\NotificationPush\Entity\NotificationPushDetailEntity;
use App\Http\Resources\Api\NotificationPush\GetNotificationPushDetailActionResource;

final class GetNotificationPushDetailActionResponder
{
    public function __invoke(NotificationPushDetailEntity $entity): GetNotificationPushDetailActionResource
    {
        $resource = $this->makeResource($entity);
        return new GetNotificationPushDetailActionResource($resource);
    }

    public function makeResource(NotificationPushDetailEntity $entity)
    {
        return [
            'notification_push' => $entity->notification_push,
            'status_code' => $entity->status_code,
        ];
    }
}
