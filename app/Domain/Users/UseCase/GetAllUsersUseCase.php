<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Users\Entity\UsersEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllUsersUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-users';

    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function __invoke(UsersEntity $entity): UsersEntity
    {
        // list-users権限 または group_invite_permission エンタイトルメントを持つユーザーはアクセス可能
        $user = auth()->user();
        if (!$user->hasPermission('list-users') && !$user->hasEntitlement('group_invite_permission')) {
            $this->authorize(); // UnauthorizedException をスロー
        }

        $data = $this->usersRepository->getAllUsers($entity);

        $users = collect($data->items())->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'avatar_url' => CommonComponent::getFullUrl($user->avatar_url),
                'is_system_admin' => $user->isSystemAdmin(),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        })->toArray();

        $entity->setUsers($users);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
