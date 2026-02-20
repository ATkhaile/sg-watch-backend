<?php

namespace App\Http\Responders\Api\Stripe;

use App\Domain\Stripe\Entity\CancelResponseEntity;
use App\Http\Resources\Api\Stripe\CancelResource;

final class CancelResponder
{
    public function __invoke(CancelResponseEntity $responseEntity): CancelResource
    {
        $resourceAry = $this->makeResource($responseEntity);
        return new CancelResource($resourceAry);
    }

    private function makeResource(CancelResponseEntity $responseEntity): array
    {
        return [
            'message' => $responseEntity->message,
            'data' => $responseEntity->data,
            'redirect' => $responseEntity->redirect,
            'status' => $responseEntity->statusCode->value,
        ];
    }
}
