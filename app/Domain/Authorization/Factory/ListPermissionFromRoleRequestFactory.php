<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\ListPermissionFromRoleRequestEntity;
use App\Http\Requests\Api\Authorization\ListPermissionFromRoleRequest;

class ListPermissionFromRoleRequestFactory
{
    public function createFromRequest(ListPermissionFromRoleRequest $request): ListPermissionFromRoleRequestEntity
    {
        $entity = new ListPermissionFromRoleRequestEntity;
        $entity->setRoleId((int)$request->route('role_id'));
        return $entity;
    }
}
