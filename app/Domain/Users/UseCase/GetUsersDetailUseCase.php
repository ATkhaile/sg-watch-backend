<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Users\Entity\UsersDetailEntity;
use App\Domain\Users\Entity\GetUsersDetailRequestEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Exceptions\Domain\NotFoundResourceException;

final class GetUsersDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-users';

    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function __invoke(GetUsersDetailRequestEntity $requestEntity): UsersDetailEntity
    {
        $this->authorize();

        $usersId = $requestEntity->getId();
        $users = $this->usersRepository->getUsersDetail($usersId);

        if (!$users) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return $users;
    }
}
