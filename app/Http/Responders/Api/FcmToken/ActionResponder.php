<?php

namespace App\Http\Responders\Api\FcmToken;

use App\Domain\FcmToken\Entity\StatusEntity;
use App\Http\Resources\Api\FcmToken\ActionResource;

class ActionResponder
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
