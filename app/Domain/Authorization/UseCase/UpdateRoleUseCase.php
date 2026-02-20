<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\UpdateRoleRequestEntity;
use App\Domain\Authorization\Repository\RoleRepository;
use App\Enums\StatusCode;

final class UpdateRoleUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-role';

    public function __construct(
        private RoleRepository $roleRepository
    ) {
    }

    public function __invoke(UpdateRoleRequestEntity $requestEntity, string $id): StatusEntity
    {
        $this->authorize();

        $success = $this->roleRepository->update($requestEntity, $id);
        if (!$success) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('authorization.role.update.failed')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('authorization.role.update.success')
            );
        }
    }
}
