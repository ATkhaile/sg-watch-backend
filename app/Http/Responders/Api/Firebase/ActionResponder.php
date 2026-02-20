<?php

namespace App\Http\Responders\Api\Firebase;

use App\Domain\Firebase\Entity\StatusEntity;
use App\Http\Resources\Api\Firebase\ActionResource;

final class ActionResponder
{
    public function __invoke(StatusEntity $statusEntity): ActionResource
    {
        $resourceAry = $this->makeResource($statusEntity);
        return new ActionResource($resourceAry);
    }

    private function makeResource(StatusEntity $statusEntity): array
    {
        return [
            'status_code' => $statusEntity->getStatus(),
            'message' => $statusEntity->getMessage(),
        ];
    }
}
