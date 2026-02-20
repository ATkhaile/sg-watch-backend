<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Users\Entity\GetUsersWithStoriesRequestEntity;
use App\Domain\Users\Entity\GetUsersWithStoriesEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetUsersWithStoriesUseCase
{
    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function __invoke(GetUsersWithStoriesRequestEntity $requestEntity): GetUsersWithStoriesEntity
    {
        $data = $this->usersRepository->getUsersWithStories($requestEntity);

        $users = collect($data->items())->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'avatar_url' => CommonComponent::getFullUrl($user->avatar_url),
                'stories_count' => $user->stories_flag_count ?? 0,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        })->toArray();

        $entity = new GetUsersWithStoriesEntity();
        $entity->setUsers($users);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
