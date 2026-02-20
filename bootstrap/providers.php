<?php

return [
    App\Providers\ApiResponseServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    App\Providers\ConsoleServiceProvider::class,
    App\Providers\Domain\PostCommentsProvider::class,
    App\Providers\Domain\PaymentTriggerDomainProvider::class,
    App\Providers\Domain\ProductDomainProvider::class,
    App\Providers\Domain\UserPurchasedPlanDomainProvider::class,
    App\Providers\Domain\UserPurchasedProductDomainProvider::class,
    App\Providers\Domain\EntitlementDomainProvider::class,
    App\Providers\Domain\MusicDomainProvider::class,
    App\Providers\Domain\GroupJoinRequestDomainProvider::class,
    App\Providers\Domain\UserCommunitySettingDomainProvider::class,
    App\Providers\LocaleServiceProvider::class,
    Barryvdh\Debugbar\ServiceProvider::class,
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
];
