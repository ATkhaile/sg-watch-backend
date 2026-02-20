<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\DeletePermissionRequestEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Domain\Shared\Concerns\RequiresPermission;
use App\Enums\StatusCode;

final class DeletePermissionUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-permission';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(DeletePermissionRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->permissionRepository->delete($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('authorization.permission.delete.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('authorization.permission.delete.failed')
            );
        }
    }
}
