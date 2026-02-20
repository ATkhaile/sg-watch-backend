<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class AdminHandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $routeName = \Route::currentRouteName();
        $dataSession = '';
        if (session()->get('Message.flash')) {
            $dataSession = session()->get('Message.flash')[0];
            session()->forget('Message.flash');
        }
        return array_merge(parent::share($request), [
            'routeName' => $routeName,
            'dataSession' => $dataSession,
            'menuDashboardActive' => in_array($routeName, ['admin.dashboard.index']),
            'menuUserActive' => in_array($routeName, ['admin.user.index', 'admin.user.create', 'admin.user.edit', 'admin.user.store', 'admin.user.update']),
            'isPageGuest' => in_array($routeName, ['admin.login.index', 'admin.login.store']),
        ]);
    }
}
