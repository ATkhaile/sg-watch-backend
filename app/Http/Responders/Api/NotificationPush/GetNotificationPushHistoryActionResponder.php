<?php

namespace App\Http\Responders\Api\NotificationPush;

use App\Domain\NotificationPush\Entity\NotificationPushHistoryEntity;
use App\Http\Resources\Api\NotificationPush\GetNotificationPushHistoryActionResource;

final class GetNotificationPushHistoryActionResponder
{
    public function __invoke(NotificationPushHistoryEntity $entity): GetNotificationPushHistoryActionResource
    {
        $resource = $this->makeResource($entity);
        return new GetNotificationPushHistoryActionResource($resource);
    }

    private function makeResource(NotificationPushHistoryEntity $entity): array
    {
        return [
            'status_code' => $entity->statusCode,
            'data' => [
                'histories'  => $entity->histories,
                'pagination' => $entity->pagination,
            ],
        ];
    }
}
