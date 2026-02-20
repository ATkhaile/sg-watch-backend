<?php

namespace App\Http\Responders\Api\Stripe;

use App\Domain\Stripe\Entity\StatusEntity;
use App\Http\Resources\Api\Stripe\RequestCancelResource;

final class RequestCancelResponder
{
    public function __invoke(StatusEntity $statusEntity): RequestCancelResource
    {
        $resourceAry = $this->makeResource($statusEntity);
        return new RequestCancelResource($resourceAry);
    }

    private function makeResource(StatusEntity $statusEntity): array
    {
        return [
            'message' => $statusEntity->message,
            'redirect' => $statusEntity->data['redirect'] ?? null,
            'status' => $statusEntity->statusCode->value,
        ];
    }
}
