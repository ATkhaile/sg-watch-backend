<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\GetUsersDetailRequestEntity;
use App\Http\Requests\Api\Users\FindUsersRequest;

class GetUsersDetailRequestFactory
{
    public function createFromRequest(FindUsersRequest $request): GetUsersDetailRequestEntity
    {
        $entity = new GetUsersDetailRequestEntity;
        $entity->setId((int)$request->route('id'));
        return $entity;
    }
}
