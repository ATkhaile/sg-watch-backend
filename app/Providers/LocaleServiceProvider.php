<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class LocaleServiceProvider extends ServiceProvider
{
    protected $availableLocales = ['ja', 'en', 'vi', 'ko', 'fr', 'de', 'es', 'pt', 'ru', 'it', 'zh'];

    public function register(): void
    {
        //
    }
    public function boot(Request $request): void
    {
        $defaultLocale = 'ja';

        // Get locale from query param only (ignore Accept-Language header)
        $requestedLocale = $request->input('lang');

        if ($requestedLocale) {
            // Extract primary language code (e.g., 'en-US' -> 'en', 'ja-JP' -> 'ja')
            $locale = strtolower($requestedLocale);
            $locale = explode('-', $locale)[0]; // Get primary language code
            $locale = explode('_', $locale)[0]; // Handle underscore format

            // Set locale if available, otherwise use default
            if (in_array($locale, $this->availableLocales)) {
                App::setLocale($locale);
            } else {
                App::setLocale($defaultLocale);
            }
        } else {
            // Always use default (Japanese) if no lang parameter
            App::setLocale($defaultLocale);
        }
    }
}
