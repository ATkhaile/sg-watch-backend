<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Users\Entity\StatusEntity;
use App\Domain\Users\Entity\DeleteUsersRequestEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;
use App\Models\User;

final class DeleteUsersUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-users';

    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function __invoke(DeleteUsersRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        // システム管理者は退会不可
        $user = User::find($requestEntity->getId());
        if ($user && $user->is_system_admin) {
            return new StatusEntity(
                statusCode: StatusCode::FORBIDDEN,
                message: '管理者は退会できません'
            );
        }

        if ($this->usersRepository->delete($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('users.delete.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('users.delete.failed')
            );
        }
    }
}
