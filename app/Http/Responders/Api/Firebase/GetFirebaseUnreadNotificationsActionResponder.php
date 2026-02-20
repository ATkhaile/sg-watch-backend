<?php

namespace App\Http\Responders\Api\Firebase;

use App\Domain\Firebase\Entity\GetFirebaseUnreadNotificationsEntity;
use App\Http\Resources\Api\Firebase\GetFirebaseUnreadNotificationsActionResource;

final class GetFirebaseUnreadNotificationsActionResponder
{
    public function __invoke(GetFirebaseUnreadNotificationsEntity $entity): GetFirebaseUnreadNotificationsActionResource
    {
        $resource = $this->makeResource($entity);
        return new GetFirebaseUnreadNotificationsActionResource($resource);
    }

    private function makeResource(GetFirebaseUnreadNotificationsEntity $entity): array
    {
        return [
            'status_code' => $entity->getStatusCode(),
            'data' => [
                'notifications' => $entity->getNotifications(),
            ],
        ];
    }
}
