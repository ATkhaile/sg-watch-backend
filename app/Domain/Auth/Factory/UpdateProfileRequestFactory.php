<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Entity\UpdateProfileRequestEntity;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UpdateProfileRequestFactory
{
    public function createFromRequest(UpdateProfileRequest $request): UpdateProfileRequestEntity
    {
        $avatarUrl = null;
        $hasAvatar = false;

        if ($request->hasFile('avatar')) {
            $user = auth()->user();
            if ($user && $user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                Storage::disk('public')->delete($user->avatar_url);
            }
            $avatarUrl = $request->file('avatar')->store('avatars', 'public');
            $hasAvatar = true;
        }

        return new UpdateProfileRequestEntity(
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            gender: $request->input('gender'),
            birthday: $request->input('birthday'),
            avatarUrl: $avatarUrl,
            hasFirstName: $request->has('first_name'),
            hasLastName: $request->has('last_name'),
            hasGender: $request->has('gender'),
            hasBirthday: $request->has('birthday'),
            hasAvatarUrl: $hasAvatar,
        );
    }
}
