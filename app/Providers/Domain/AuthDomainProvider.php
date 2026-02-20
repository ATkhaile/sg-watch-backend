<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

/**
 * アカウントドメインのプロバイダ
 */
class AuthDomainProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Auth\Repository\UserRepository::class,
            \App\Domain\Auth\Infrastructure\DbUserInfrastructure::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
