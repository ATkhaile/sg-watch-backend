<?php

namespace App\Http\Responders\Api\NotificationPush;

use App\Domain\NotificationPush\Entity\StatusEntity;
use App\Http\Resources\Api\NotificationPush\ActionResource;

final class ActionResponder
{
    public function __invoke(StatusEntity $statusEntity): ActionResource
    {
        $resource = [
            'status_code' => $statusEntity->statusCode,
            'message'     => $statusEntity->message,
        ];

        return new ActionResource($resource);
    }
}
