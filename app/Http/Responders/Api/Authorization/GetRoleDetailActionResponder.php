<?php

namespace App\Http\Responders\Api\Authorization;

use App\Domain\Authorization\Entity\RoleDetailEntity;
use App\Http\Resources\Api\Authorization\GetRoleDetailActionResource;

final class GetRoleDetailActionResponder
{
    public function __invoke(RoleDetailEntity $roleEntity): GetRoleDetailActionResource
    {
        $resource = $this->makeResource($roleEntity);
        return new GetRoleDetailActionResource($resource);
    }

    public function makeResource(RoleDetailEntity $roleEntity)
    {
        return [
            'role' => $roleEntity->jsonSerialize(),
            'status_code' => $roleEntity->getStatus(),
        ];
    }
}
