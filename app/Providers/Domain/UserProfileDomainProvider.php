<?php
namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class UserProfileDomainProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\UserProfile\Repository\UserProfileRepository::class,
            \App\Domain\UserProfile\Infrastructure\DbUserProfileInfrastructure::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
