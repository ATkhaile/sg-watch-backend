<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\ListRoleFromUserRequestEntity;
use App\Domain\Authorization\Repository\RoleRepository;
use App\Enums\StatusCode;

final class ListRoleFromUserUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-role-from-user';

    public function __construct(
        private RoleRepository $roleRepository
    ) {
    }

    public function __invoke(ListRoleFromUserRequestEntity $requestEntity): array
    {
        $this->authorize();

        $user_roles = $this->roleRepository->listRoleFromUser($requestEntity);

        // ロールが0件でも正常なレスポンスを返す
        return [
            'status_code' => StatusCode::OK,
            'roles' => $user_roles
        ];
    }
}
