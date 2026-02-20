<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\UpdateProfileRequestEntity;
use App\Domain\Auth\Entity\UserInfoEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Components\CommonComponent;

final class UpdateProfileUseCase
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdateProfileRequestEntity $requestEntity): UserInfoEntity
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        $data = [];
        if ($requestEntity->hasFirstName()) {
            $data['first_name'] = $requestEntity->getFirstName();
        }
        if ($requestEntity->hasLastName()) {
            $data['last_name'] = $requestEntity->getLastName();
        }
        if ($requestEntity->hasGender()) {
            $data['gender'] = $requestEntity->getGender();
        }
        if ($requestEntity->hasBirthday()) {
            $data['birthday'] = $requestEntity->getBirthday();
        }
        if ($requestEntity->hasAvatarUrl()) {
            $data['avatar_url'] = $requestEntity->getAvatarUrl();
        }

        if (!empty($data)) {
            $result = $this->userRepository->updateProfile((int) $user->id, $data);
            if (!$result) {
                throw new \Exception(__('auth.update_profile.failed'));
            }
            $user->refresh();
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
