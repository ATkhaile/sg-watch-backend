<?php

namespace App\Http\Responders\Api\Users;

use App\Domain\Users\Entity\UserSessionDevicesEntity;
use App\Http\Resources\Api\Users\GetUserSessionDevicesResource;

final class GetUserSessionDevicesResponder
{
    public function __invoke(UserSessionDevicesEntity $entity): GetUserSessionDevicesResource
    {
        return new GetUserSessionDevicesResource($entity);
    }
}
