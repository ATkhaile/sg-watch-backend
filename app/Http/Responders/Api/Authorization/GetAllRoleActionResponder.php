<?php

namespace App\Http\Responders\Api\Authorization;

use App\Domain\Authorization\Entity\RolesEntity;
use App\Http\Resources\Api\Authorization\GetAllRoleActionResource;

final class GetAllRoleActionResponder
{
    public function __invoke(RolesEntity $rolesEntity): GetAllRoleActionResource
    {
        $resource = $this->makeResource($rolesEntity);
        return new GetAllRoleActionResource($resource);
    }

    public function makeResource(RolesEntity $rolesEntity)
    {
        return [
            'status_code' => $rolesEntity->getStatus(),
            'data' => [
                'roles' => $rolesEntity->getRoles(),
                'pagination' =>  $rolesEntity->getPagination(),
            ],
        ];
    }
}
