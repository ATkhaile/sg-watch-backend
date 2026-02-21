<?php

namespace App\Domain\Sessions\Factory;

use App\Domain\Sessions\Entity\TerminateSessionRequestEntity;
use App\Http\Requests\Api\Sessions\TerminateSessionRequest;

class TerminateSessionRequestFactory
{
    public function createFromRequest(TerminateSessionRequest $request): TerminateSessionRequestEntity
    {
        return new TerminateSessionRequestEntity(
            sessionId: $request->id
        );
    }
}
