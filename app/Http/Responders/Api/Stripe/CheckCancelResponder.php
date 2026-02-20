<?php

namespace App\Http\Responders\Api\Stripe;

use App\Domain\Stripe\Entity\StatusEntity;
use App\Http\Resources\Api\Stripe\CheckCancelResource;

final class CheckCancelResponder
{
    public function __invoke(StatusEntity $statusEntity): CheckCancelResource
    {
        $resourceAry = $this->makeResource($statusEntity);
        return new CheckCancelResource($resourceAry);
    }

    private function makeResource(StatusEntity $statusEntity): array
    {
        return [
            'message' => $statusEntity->message,
            'redirect' => $statusEntity->data['redirect'] ?? '',
            'status' => $statusEntity->statusCode->value,
        ];
    }
}
