<?php

namespace App\Domain\Notifications\Factory;

use App\Domain\Notifications\Entity\DeleteNotificationsRequestEntity;
use App\Http\Requests\Api\Notifications\DeleteNotificationsRequest;

class DeleteNotificationsRequestFactory
{
    public function createFromRequest(DeleteNotificationsRequest $request): DeleteNotificationsRequestEntity
    {
        return new DeleteNotificationsRequestEntity(
            id: $request->route('id')
        );
    }
}
