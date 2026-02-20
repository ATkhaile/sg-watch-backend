<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Users\Entity\StatusEntity;
use App\Domain\Users\Entity\UpdateUsersRequestEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;

final class UpdateUsersUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-users';

    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function __invoke(UpdateUsersRequestEntity $requestEntity, string $id): StatusEntity
    {
        $this->authorize();

        $success = $this->usersRepository->update($requestEntity, $id);
        if (!$success) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('users.update.failed')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('users.update.success')
            );
        }
    }
}
