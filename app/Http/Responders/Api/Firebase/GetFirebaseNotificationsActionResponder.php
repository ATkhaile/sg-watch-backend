<?php

namespace App\Http\Responders\Api\Firebase;

use App\Domain\Firebase\Entity\FirebaseNotificationEntity;
use App\Http\Resources\Api\Firebase\GetFirebaseNotificationsActionResource;

final class GetFirebaseNotificationsActionResponder
{
    public function __invoke(FirebaseNotificationEntity $firebaseNotificationEntity): GetFirebaseNotificationsActionResource
    {
        $resource = $this->makeResource($firebaseNotificationEntity);
        return new GetFirebaseNotificationsActionResource($resource);
    }

    private function makeResource(FirebaseNotificationEntity $firebaseNotificationEntity): array
    {
        $data = $firebaseNotificationEntity->getNotifications();
        return [
            'status_code' => $firebaseNotificationEntity->getStatusCode(),
            'data' => [
                'notifications' => $data,
                'pagination' => $firebaseNotificationEntity->getPagination(),
                'notification_unread_count' => collect($data)->first()['notification_unread_count'] ?? 0
            ],
        ];
    }
}
