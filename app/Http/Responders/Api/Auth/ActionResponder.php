<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Entity\StatusEntity;
use App\Http\Resources\Api\Auth\ActionResource;

final class ActionResponder
{
    public function __invoke(StatusEntity $statusEntity): ActionResource
    {
        $resourceAry = $this->makeResource($statusEntity);
        return new ActionResource($resourceAry);
    }

    private function makeResource(StatusEntity $statusEntity): array
    {
        $resource = [
            'status_code' => $statusEntity->getStatus(),
            'message' => $statusEntity->getMessage(),
        ];

        if ($statusEntity->getToken()) {
            $resource['token'] = $statusEntity->getToken();
        }

        return $resource;
    }
}
