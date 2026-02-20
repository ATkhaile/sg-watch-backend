<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\UpdateUsersRequestEntity;
use App\Http\Requests\Api\Users\UpdateUsersRequest;

class UpdateUsersRequestFactory
{
    public function createFromRequest(UpdateUsersRequest $request): UpdateUsersRequestEntity
    {
        return new UpdateUsersRequestEntity(
            first_name: $request->has('first_name') ? $request->input('first_name') : null,
            last_name: $request->has('last_name') ? $request->input('last_name') : null,
            email: $request->has('email') ? $request->input('email') : null,
            gender: $request->has('gender') ? $request->input('gender') : null,
            password: $request->has('password') ? $request->input('password') : null,
        );
    }
}
