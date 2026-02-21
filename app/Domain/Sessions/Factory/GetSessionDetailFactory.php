<?php

namespace App\Domain\Sessions\Factory;

use App\Domain\Sessions\Entity\GetSessionDetailRequestEntity;
use App\Http\Requests\Api\Sessions\GetSessionDetailRequest;

class GetSessionDetailFactory
{
    public function create(GetSessionDetailRequest $request): GetSessionDetailRequestEntity
    {
        return new GetSessionDetailRequestEntity(
            sessionId: $request->input('id')
        );
    }
}
