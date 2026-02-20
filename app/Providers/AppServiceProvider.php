<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use App\Models\Role;
use App\Observers\RoleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Auth & Sessions
        $this->app->register(\App\Providers\Domain\AuthDomainProvider::class);
        $this->app->register(\App\Providers\Domain\AuthorizationDomainProvider::class);
        $this->app->register(\App\Providers\Domain\SessionsDomainProvider::class);

        // Users
        $this->app->register(\App\Providers\Domain\UsersDomainProvider::class);

        // Chat
        $this->app->register(\App\Providers\Domain\ChatDomainProvider::class);

        // Notifications & Firebase
        $this->app->register(\App\Providers\Domain\NotificationsDomainProvider::class);
        $this->app->register(\App\Providers\Domain\NotificationPushDomainProvider::class);
        $this->app->register(\App\Providers\Domain\FirebaseDomainProvider::class);
        $this->app->register(\App\Providers\Domain\FcmTokenDomainProvider::class);

        // Comment
        $this->app->register(\App\Providers\Domain\CommentDomainProvider::class);

        // Stripe
        $this->app->register(\App\Providers\Domain\StripeDomainProvider::class);
        $this->app->register(\App\Providers\Domain\StripeAccountDomainProvider::class);

        // OAuth Providers
        $this->app->register(\App\Providers\Domain\GoogleDomainProvider::class);

        // Address
        $this->app->register(\App\Providers\Domain\AddressDomainProvider::class);

        // Shop
        $this->app->register(\App\Providers\Domain\ShopProductDomainProvider::class);
        $this->app->register(\App\Providers\Domain\ShopCartDomainProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        // Observers
        Role::observe(RoleObserver::class);
    }
}
