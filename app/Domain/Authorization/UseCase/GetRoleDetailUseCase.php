<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\RoleDetailEntity;
use App\Domain\Authorization\Entity\GetRoleDetailRequestEntity;
use App\Domain\Authorization\Repository\RoleRepository;
use App\Exceptions\Domain\NotFoundResourceException;

final class GetRoleDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-role';

    public function __construct(
        private RoleRepository $roleRepository
    ) {
    }

    public function __invoke(GetRoleDetailRequestEntity $requestEntity): RoleDetailEntity
    {
        $this->authorize();

        $roleId = $requestEntity->getId();
        $role = $this->roleRepository->getRoleDetail($roleId);

        if (!$role) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return $role;
    }
}
