<?php

namespace App\Domain\Google\Factory;

use App\Domain\Google\Entity\GoogleCallbackRequestEntity;
use App\Http\Requests\Api\Google\GoogleCallbackRequest;

class GoogleCallbackRequestFactory
{
    public function createFromRequest(GoogleCallbackRequest $request): GoogleCallbackRequestEntity
    {
        return new GoogleCallbackRequestEntity(
            code: $request->input('code'),
            type: $request->input('type', null),
            redirect_url: $request->input('redirect_url', null)
        );
    }
}
