<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\GetRoleDetailRequestEntity;
use App\Http\Requests\Api\Authorization\FindRoleRequest;

class GetRoleDetailRequestFactory
{
    public function createFromRequest(FindRoleRequest $request): GetRoleDetailRequestEntity
    {
        $entity = new GetRoleDetailRequestEntity;
        $entity->setId((int)$request->route('id'));
        return $entity;
    }
}
