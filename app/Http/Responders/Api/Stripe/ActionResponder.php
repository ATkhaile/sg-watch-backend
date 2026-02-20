<?php

namespace App\Http\Responders\Api\Stripe;

use App\Domain\Stripe\Entity\StatusEntity;
use App\Http\Resources\Api\Stripe\ActionResource;

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
            'status' => $statusEntity->statusCode->value,
            'message' => $statusEntity->message,
        ];
    }
}
