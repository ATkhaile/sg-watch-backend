<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\ListRoleFromUserRequestEntity;
use App\Http\Requests\Api\Authorization\ListRoleFromUserRequest;

class ListRoleFromUserRequestFactory
{
    public function createFromRequest(ListRoleFromUserRequest $request): ListRoleFromUserRequestEntity
    {
        $entity = new ListRoleFromUserRequestEntity;
        $entity->setUserId((int)$request->route('user_id'));
        return $entity;
    }
}
