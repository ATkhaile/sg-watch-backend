<?php

namespace App\Http\Responders\Api\Dashboard;

use App\Http\Resources\Api\Dashboard\AdminGetDashboardActionResource;

final class AdminGetDashboardActionResponder
{
    public function __invoke(array $result): AdminGetDashboardActionResource
    {
        return new AdminGetDashboardActionResource($result);
    }
}
