<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\DeleteRoleRequestEntity;
use App\Domain\Authorization\Repository\RoleRepository;
use App\Enums\StatusCode;
use App\Models\Role;

final class DeleteRoleUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-role';

    public function __construct(
        private RoleRepository $roleRepository
    ) {
    }

    public function __invoke(DeleteRoleRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        // システムロールは削除不可
        $role = Role::find($requestEntity->getId());
        if ($role && $role->is_system) {
            return new StatusEntity(
                statusCode: StatusCode::FORBIDDEN,
                message: __('authorization.role.delete.system_role_cannot_be_deleted')
            );
        }

        if ($this->roleRepository->delete($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('authorization.role.delete.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('authorization.role.delete.failed')
            );
        }
    }
}
