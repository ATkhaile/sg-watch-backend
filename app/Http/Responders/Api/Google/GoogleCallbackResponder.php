<?php

namespace App\Http\Responders\Api\Google;

use App\Domain\Google\Entity\GoogleCallbackResponseEntity;
use App\Http\Resources\Api\Google\GoogleCallbackResource;

class GoogleCallbackResponder
{
    public function __invoke(GoogleCallbackResponseEntity $responseEntity): GoogleCallbackResource
    {
        $resourceArray = $this->makeResource($responseEntity);
        return new GoogleCallbackResource($resourceArray);
    }

    private function makeResource(GoogleCallbackResponseEntity $responseEntity): array
    {
        return [
            'status_code' => $responseEntity->statusCode,
            'token' => $responseEntity->token,
            'message' => $responseEntity->message,
            'is_first_login' => $responseEntity->isFirstLogin,
        ];
    }
}
