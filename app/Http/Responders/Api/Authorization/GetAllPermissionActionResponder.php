<?php

namespace App\Http\Responders\Api\Authorization;

use App\Domain\Authorization\Entity\PermissionsEntity;
use App\Http\Resources\Api\Authorization\GetAllPermissionActionResource;

final class GetAllPermissionActionResponder
{
    public function __invoke(PermissionsEntity $permissionsEntity): GetAllPermissionActionResource
    {
        $resource = $this->makeResource($permissionsEntity);
        return new GetAllPermissionActionResource($resource);
    }

    public function makeResource(PermissionsEntity $permissionsEntity)
    {
        return [
            'status_code' => $permissionsEntity->getStatus(),
            'data' => [
                'permissions' => $permissionsEntity->getPermissions(),
                'pagination' =>  $permissionsEntity->getPagination(),
            ],
        ];
    }
}
