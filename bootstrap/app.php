<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SiteAdmin;
use App\Http\Middleware\AdminHandleInertiaRequests;
use App\Http\Middleware\AssignGuard;
use App\Http\Middleware\Admin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Http\Request;
use App\Http\Middleware\AuthSession;
use App\Http\Middleware\AuthBasic;
use App\Http\Middleware\AuthSystemAdmin;
use App\Http\Middleware\CheckAdminRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('apiMiddleware')
                ->prefix('api/v1')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORS is handled by config/cors.php
        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);

        $middleware->validateCsrfTokens(except: [
            'apple-callback',
        ]);

        $middleware->appendToGroup('siteAdmin', [
            SiteAdmin::class,
            AdminHandleInertiaRequests::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            SubstituteBindings::class,
        ]);
        $middleware->appendToGroup('apiMiddleware', [
            // \App\Http\Middleware\CorsMiddleware::class,
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\JsonUnescapeUnicode::class,
            // \App\Http\Middleware\SignatureVerify::class,
        ]);
        $middleware->alias([
            // 認証Middleware
            'auth.session' => AuthSession::class,         // JWT + セッション検証（Web/SPA）
            'auth.basic' => AuthBasic::class,             // JWTのみ（Mobile）
            'auth.system_admin' => AuthSystemAdmin::class, // JWT + isSystemAdmin()（管理者）
            // その他
            'assign.guard' => AssignGuard::class,
            'admin' => Admin::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'userLine' => \App\Http\Middleware\UserLine::class,
            'shop.golph' => \App\Http\Middleware\ShopGolph::class,
            'shop.sauna' => \App\Http\Middleware\ShopSauna::class,
            'admin.role' => CheckAdminRole::class,
            'signature.verify' => \App\Http\Middleware\SignatureVerify::class,
        ]);
        // $middleware->admin(append: [
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // if (env('APP_ENV') != 'local') {
        $exceptions->render(function (\Exception $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = 500;
                try {
                    $statusCode = $e->getStatusCode();
                } catch (\Throwable $th) {
                }
                return response()->json([
                    'message' => $e->getMessage(),
                    'status_code' => $statusCode
                ], $statusCode, [], JSON_UNESCAPED_UNICODE);
            }
        });
        // }
    })->create();
