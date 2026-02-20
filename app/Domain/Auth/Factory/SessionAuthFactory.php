<?php

namespace App\Domain\Auth\Factory;

use Illuminate\Http\Request;
use App\Domain\Auth\Entity\SessionAuthRequestEntity;

class SessionAuthFactory
{
    public function createFromRequest(Request $request): SessionAuthRequestEntity
    {
        return new SessionAuthRequestEntity(
            sessionId: $request->session_id
        );
    }
}
