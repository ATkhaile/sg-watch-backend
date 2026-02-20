<?php

namespace App\Domain\Notifications\Factory;

use App\Domain\Notifications\Entity\GetNotificationsDetailRequestEntity;
use App\Http\Requests\Api\Notifications\GetNotificationsDetailRequest;

class GetNotificationsDetailRequestFactory
{
    public function createFromRequest(GetNotificationsDetailRequest $request): GetNotificationsDetailRequestEntity
    {
        return new GetNotificationsDetailRequestEntity(
            id: $request->route('id')
        );
    }
}
