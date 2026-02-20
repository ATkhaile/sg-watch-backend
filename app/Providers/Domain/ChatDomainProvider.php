<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ChatDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Chat\Repository\ChatMessageRepository::class,
            \App\Domain\Chat\Infrastructure\DbChatMessageInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
