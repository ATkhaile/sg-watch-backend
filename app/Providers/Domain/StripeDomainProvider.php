<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class StripeDomainProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Stripe\Repository\StripeRepository::class,
            \App\Domain\Stripe\Infrastructure\DbStripeInfrastructure::class
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
