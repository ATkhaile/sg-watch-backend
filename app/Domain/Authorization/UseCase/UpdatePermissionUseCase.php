<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\UpdatePermissionRequestEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;

final class UpdatePermissionUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-permission';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(UpdatePermissionRequestEntity $requestEntity, string $id): StatusEntity
    {
        $this->authorize();

        $success = $this->permissionRepository->update($requestEntity, $id);
        if (!$success) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('authorization.permission.update.failed')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('authorization.permission.update.success')
            );
        }
    }
}
