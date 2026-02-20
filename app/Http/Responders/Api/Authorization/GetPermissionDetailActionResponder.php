<?php

namespace App\Http\Responders\Api\Authorization;

use App\Domain\Authorization\Entity\PermissionDetailEntity;
use App\Http\Resources\Api\Authorization\GetPermissionDetailActionResource;

final class GetPermissionDetailActionResponder
{
    public function __invoke(PermissionDetailEntity $permissionEntity): GetPermissionDetailActionResource
    {
        $resource = $this->makeResource($permissionEntity);
        return new GetPermissionDetailActionResource($resource);
    }

    public function makeResource(PermissionDetailEntity $permissionEntity)
    {
        return [
            'permission' => $permissionEntity->jsonSerialize(),
            'status_code' => $permissionEntity->getStatus(),
        ];
    }
}
