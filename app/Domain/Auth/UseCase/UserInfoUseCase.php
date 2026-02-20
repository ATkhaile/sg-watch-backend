<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\UserInfoEntity;
use App\Components\CommonComponent;

final class UserInfoUseCase
{
    public function __invoke(): UserInfoEntity
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        $avatarUrl = null;
        if ($user->avatar_url) {
            $avatarUrl = CommonComponent::getFullUrl($user->avatar_url);
        }

        return new UserInfoEntity(
            id: (string)$user->id,
            firstName: $user->first_name ?? '',
            lastName: $user->last_name ?? '',
            avatarUrl: $avatarUrl,
            gender: $user->gender,
            birthday: $user->birthday?->format('Y-m-d'),
            role: $user->isSystemAdmin() ? 'admin' : 'user',
            email: $user->email,
        );
    }
}
