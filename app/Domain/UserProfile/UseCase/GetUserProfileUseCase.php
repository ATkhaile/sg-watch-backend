<?php

namespace App\Domain\UserProfile\UseCase;

use App\Domain\UserProfile\Entity\UserProfileEntity;
use App\Domain\UserProfile\Repository\UserProfileRepository;
use App\Enums\StatusCode;

final class GetUserProfileUseCase
{
    public function __construct(
        private UserProfileRepository $userProfileRepository
    ) {
    }

    public function __invoke(UserProfileEntity $entity): UserProfileEntity
    {
        $user = $this->userProfileRepository->getUser($entity->accountId);

        if (!$user) {
            throw new \Exception(__('user_profile.get.failed'), StatusCode::NOT_FOUND);
        }

        return new UserProfileEntity(
            profile: $user->toArray(),
            statusCode: StatusCode::OK,
        );
    }
}
