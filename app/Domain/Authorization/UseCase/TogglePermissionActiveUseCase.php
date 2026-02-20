<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;

final class TogglePermissionActiveUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-permission';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(int $permissionId): array
    {
        $this->authorize();

        $result = $this->permissionRepository->toggleActive($permissionId);

        if (!$result) {
            return [
                'status_code' => StatusCode::NOT_FOUND,
                'success' => false,
                'message' => '権限が見つかりません',
            ];
        }

        return [
            'status_code' => StatusCode::OK,
            'success' => true,
            'message' => '権限のアクティブ状態を切り替えました',
            'data' => $result,
        ];
    }
}
