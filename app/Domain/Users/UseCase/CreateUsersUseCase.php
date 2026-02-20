<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;
use App\Domain\Users\Entity\StatusEntity;
use App\Domain\Users\Entity\CreateUsersRequestEntity;

final class CreateUsersUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-users';

    private UsersRepository $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function __invoke(CreateUsersRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->usersRepository->store($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('users.create.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('users.create.failed')
            );
        }
    }
}
