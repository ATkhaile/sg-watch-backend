<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Repository\RoleRepository;
use App\Enums\StatusCode;
use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\AttachRoleToUserRequestEntity;

final class AttachRoleToUserUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'attach-role-to-user';

    public function __construct(
        private RoleRepository $roleRepository
    ) {
    }

    public function __invoke(AttachRoleToUserRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        $userId = $requestEntity->getUserId();
        $roleIds = $requestEntity->getRoleIds();

        if ($this->roleRepository->attachToUser($userId, $roleIds)) {
            return new StatusEntity(StatusCode::OK, __('authorization.role.attach.success'));
        }

        return new StatusEntity(StatusCode::INTERNAL_ERR, __('authorization.role.attach.failed'));
    }
}
