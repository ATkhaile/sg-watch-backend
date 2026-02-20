<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class AuthorizationDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Authorization\Repository\RoleRepository::class,
            \App\Domain\Authorization\Infrastructure\DbRoleInfrastructure::class
        );

        $this->app->bind(
            \App\Domain\Authorization\Repository\PermissionRepository::class,
            \App\Domain\Authorization\Infrastructure\DbPermissionInfrastructure::class
        );
    }

    public function boot(): void
    {
        //
    }
}
