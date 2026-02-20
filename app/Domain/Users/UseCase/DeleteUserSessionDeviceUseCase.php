<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Users\Entity\DeleteUserSessionDeviceRequestEntity;
use App\Domain\Users\Entity\StatusEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;

final class DeleteUserSessionDeviceUseCase
{
    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function __invoke(DeleteUserSessionDeviceRequestEntity $entity): StatusEntity
    {
        if ($this->usersRepository->deleteSessionDevice($entity->id)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('users.delete.success')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::INTERNAL_ERR,
            message: __('users.delete.failed')
        );
    }
}
