<?php

namespace App\Domain\Google\Factory;

use App\Domain\Google\Entity\GoogleAppLoginRequestEntity;
use App\Http\Requests\Api\Google\GoogleAppLoginRequest;

class GoogleAppLoginRequestFactory
{
    public function createFromRequest(GoogleAppLoginRequest $request): GoogleAppLoginRequestEntity
    {
        return new GoogleAppLoginRequestEntity(
            token: $request->get('token'),
            type: $request->get('type'),
        );
    }
}