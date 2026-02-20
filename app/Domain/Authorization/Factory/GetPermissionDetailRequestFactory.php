<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\GetPermissionDetailRequestEntity;
use App\Http\Requests\Api\Authorization\FindPermissionRequest;

class GetPermissionDetailRequestFactory
{
    public function createFromRequest(FindPermissionRequest $request): GetPermissionDetailRequestEntity
    {
        $entity = new GetPermissionDetailRequestEntity;
        $entity->setId((int)$request->route('id'));
        return $entity;
    }
}
