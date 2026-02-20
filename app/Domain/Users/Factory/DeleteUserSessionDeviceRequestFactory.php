<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\DeleteUserSessionDeviceRequestEntity;
use App\Http\Requests\Api\Users\DeleteUserSessionDeviceRequest;

class DeleteUserSessionDeviceRequestFactory
{
    public function createFromRequest(DeleteUserSessionDeviceRequest $request): DeleteUserSessionDeviceRequestEntity
    {
        return new DeleteUserSessionDeviceRequestEntity(
            id: (int) $request->route('id')
        );
    }
}
