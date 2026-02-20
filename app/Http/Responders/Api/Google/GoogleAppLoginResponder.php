<?php

namespace App\Http\Responders\Api\Google;

use App\Domain\Google\Entity\GoogleAppLoginResponseEntity;
use App\Http\Resources\Api\Google\GoogleAppLoginResource;

class GoogleAppLoginResponder
{
    public function __invoke(GoogleAppLoginResponseEntity $responseEntity): GoogleAppLoginResource
    {
        $resourceArray = $this->makeResource($responseEntity);
        return new GoogleAppLoginResource($resourceArray);
    }

    private function makeResource(GoogleAppLoginResponseEntity $responseEntity): array
    {
        return [
            'status_code' => $responseEntity->statusCode,
            'token' => $responseEntity->token,
            'message' => $responseEntity->message,
        ];
    }
}