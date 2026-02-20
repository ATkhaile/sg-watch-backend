<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Kernel as ConsoleKernel;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            ConsoleKernel::class
        );
    }
}
