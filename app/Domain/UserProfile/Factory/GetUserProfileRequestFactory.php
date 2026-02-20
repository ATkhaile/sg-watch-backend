<?php

namespace App\Domain\UserProfile\Factory;

use App\Http\Requests\Api\UserProfile\GetUserProfileRequest;
use App\Domain\UserProfile\Entity\UserProfileEntity;
use Illuminate\Support\Facades\Auth;

class GetUserProfileRequestFactory
{
    public function createFromRequest(GetUserProfileRequest $request): UserProfileEntity
    {
        $accountId = Auth::guard('member')->id();

        return new UserProfileEntity(
            accountId: $accountId,
        );
    }
}
