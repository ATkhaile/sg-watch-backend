<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\ListPermissionFromUserRequestEntity;
use App\Http\Requests\Api\Authorization\ListPermissionFromUserRequest;

class ListPermissionFromUserRequestFactory
{
    public function createFromRequest(ListPermissionFromUserRequest $request): ListPermissionFromUserRequestEntity
    {
        $entity = new ListPermissionFromUserRequestEntity;
        $entity->setUserId((int)$request->route('user_id'));
        return $entity;
    }
}
