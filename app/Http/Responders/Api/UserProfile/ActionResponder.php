<?php

namespace App\Http\Responders\Api\UserProfile;

use App\Domain\UserProfile\Entity\StatusEntity;
use App\Http\Resources\Api\UserProfile\ActionResource;

final class ActionResponder
{
    public function __invoke(StatusEntity $statusEntity): ActionResource
    {
        $resource = $this->makeResource($statusEntity);
        return new ActionResource($resource);
    }

    private function makeResource(StatusEntity $statusEntity): array
    {
        return [
            'status_code' => $statusEntity->getStatus(),
            'message' => $statusEntity->getMessage(),
        ];
    }
}
