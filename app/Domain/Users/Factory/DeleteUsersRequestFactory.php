<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\DeleteUsersRequestEntity;
use App\Http\Requests\Api\Users\DeleteUsersRequest;

class DeleteUsersRequestFactory
{
    public function createFromRequest(DeleteUsersRequest $request): DeleteUsersRequestEntity
    {
        return new DeleteUsersRequestEntity(
            id: (int)$request->route('id')
        );
    }
}
