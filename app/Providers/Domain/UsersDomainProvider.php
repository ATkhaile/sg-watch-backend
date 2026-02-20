<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class UsersDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Users\Repository\UsersRepository::class,
            \App\Domain\Users\Infrastructure\DbUsersInfrastructure::class,
        );

        $this->app->bind(
            \App\Domain\Users\Repository\UserMembershipRepository::class,
            \App\Domain\Users\Infrastructure\DbUserMembershipInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
