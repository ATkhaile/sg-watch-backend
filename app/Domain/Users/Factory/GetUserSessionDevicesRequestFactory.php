<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\UserSessionDevicesEntity;
use App\Http\Requests\Api\Users\GetUserSessionDevicesRequest;

class GetUserSessionDevicesRequestFactory
{
    public function createFromRequest(GetUserSessionDevicesRequest $request): UserSessionDevicesEntity
    {
        return new UserSessionDevicesEntity(
            user_id: (int) $request->input('user_id'),
            status: $request->input('status'),
            sortBy: $request->input('sort_by', 'id'),
            sortDirection: $request->input('sort_direction', 'desc'),
            limit: (int) $request->input('limit', 10),
            page: (int) $request->input('page', 1),
        );
    }
}
