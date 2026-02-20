<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\UpdateProfileRequestEntity;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;

class UpdateProfileRequestFactory
{
    public function createFromRequest(UpdateProfileRequest $request): UpdateProfileRequestEntity
    {
        return new UpdateProfileRequestEntity(
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            gender: $request->input('gender'),
            birthday: $request->input('birthday'),
            avatarUrl: $request->input('avatar_url'),
            hasFirstName: $request->has('first_name'),
            hasLastName: $request->has('last_name'),
            hasGender: $request->has('gender'),
            hasBirthday: $request->has('birthday'),
            hasAvatarUrl: $request->has('avatar_url'),
        );
    }
}
