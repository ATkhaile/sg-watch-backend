<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class AdminUserDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\AdminUser\Repository\AdminUserRepository::class,
            \App\Domain\AdminUser\Infrastructure\DbAdminUserInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
