<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class StripeAccountDomainProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\StripeAccount\Repository\StripeAccountRepository::class,
            \App\Domain\StripeAccount\Infrastructure\DbStripeAccountInfrastructure::class
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
