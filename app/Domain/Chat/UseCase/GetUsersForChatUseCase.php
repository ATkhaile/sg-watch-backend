<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Entity\UsersEntity;
use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetUsersForChatUseCase
{
    public function __construct(
        private ChatMessageRepository $usersRepository
    ) {
    }

    public function __invoke(UsersEntity $entity): UsersEntity
    {
        $data = $this->usersRepository->getAllUsers($entity);

        $users = collect($data->items())->map(function ($users) {
            return [
                'id' => $users->id,
                'first_name' => $users->first_name,
                'last_name' => $users->last_name,
                'email' => $users->email,
                'admin' => $users->isSystemAdmin(),
                'created_at' => $users->created_at,
                'updated_at' => $users->updated_at,
            ];
        })->toArray();

        $entity->setUsers($users);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
