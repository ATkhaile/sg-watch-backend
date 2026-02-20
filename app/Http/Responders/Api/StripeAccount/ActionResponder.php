<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StatusEntity;
use App\Http\Resources\Api\StripeAccount\ActionResource;

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
