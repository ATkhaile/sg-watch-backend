<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;
use App\Enums\StatusCode;

final class GetUsecaseGroupsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-permission';

    public function __invoke(): array
    {
        $this->authorize();

        $groupsConfig = config('usecase_groups', []);

        $groups = collect($groupsConfig)->map(function ($config, $name) {
            return [
                'name' => $name,
                'display_name' => $config['display_name'] ?? $name,
                'description' => $config['description'] ?? '',
                'icon' => $config['icon'] ?? 'folder',
                'sort_order' => $config['sort_order'] ?? 999,
            ];
        })->sortBy('sort_order')->values()->toArray();

        return [
            'status_code' => StatusCode::OK,
            'success' => true,
            'data' => [
                'groups' => $groups,
            ],
        ];
    }
}
