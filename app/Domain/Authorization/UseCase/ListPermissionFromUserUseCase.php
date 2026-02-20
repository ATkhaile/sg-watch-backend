<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\ListPermissionFromUserRequestEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;

final class ListPermissionFromUserUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-permission-from-user';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(ListPermissionFromUserRequestEntity $requestEntity): array
    {
        $this->authorize();

        $user_permissions = $this->permissionRepository->listPermissionFromUser($requestEntity->getUserId());

        // 権限が0件でも正常なレスポンスを返す
        return [
            'status_code' => StatusCode::OK,
            'permissions' => $user_permissions
        ];
    }
}
