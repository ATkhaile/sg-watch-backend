<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\CreateUsersRequestEntity;
use App\Http\Requests\Api\Users\CreateUsersRequest;

class CreateUsersRequestFactory
{
    public function createFromRequest(CreateUsersRequest $request): CreateUsersRequestEntity
    {
        return new CreateUsersRequestEntity(
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }
}
