<?php

namespace App\Domain\Sessions\Factory;

use App\Domain\Sessions\Entity\TerminateSessionInUserRequestEntity;
use App\Http\Requests\Api\Sessions\TerminateSessionInUserRequest;

class TerminateSessionInUserRequestFactory
{
    public function createFromRequest(TerminateSessionInUserRequest $request): TerminateSessionInUserRequestEntity
    {
        return new TerminateSessionInUserRequestEntity(
            sessionId: $request->id
        );
    }
}
