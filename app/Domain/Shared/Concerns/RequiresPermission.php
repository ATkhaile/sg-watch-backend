<?php

namespace App\Domain\Shared\Concerns;

use App\Exceptions\Domain\UnauthorizedException;

trait RequiresPermission
{
    protected function authorize(): void
    {
        if (!defined('static::PERMISSION')) {
            return;
        }

        $user = auth()->user();

        if (!$user) {
            throw new UnauthorizedException('認証されていません。');
        }

        $permission = static::PERMISSION;

        if (!$user->hasPermission($permission)) {
            throw new UnauthorizedException(
                "権限 '{$permission}' がありません。"
            );
        }
    }

    protected function authorizeAny(array $permissions): void
    {
        $user = auth()->user();

        if (!$user) {
            throw new UnauthorizedException('認証されていません。');
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return;
            }
        }

        throw new UnauthorizedException(
            '必要な権限がありません。'
        );
    }

    protected function authorizeAll(array $permissions): void
    {
        $user = auth()->user();

        if (!$user) {
            throw new UnauthorizedException('認証されていません。');
        }

        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
                throw new UnauthorizedException(
                    "権限 '{$permission}' がありません。"
                );
            }
        }
    }
}
