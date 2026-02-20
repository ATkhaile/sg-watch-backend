<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            $routeName = \Route::currentRouteName();
            if ($request->ajax() || in_array($routeName, ['admin.user.destroy'])) {
                abort(401, '許可されていないアクション。');
            }
            return redirect(route('admin.login.index', ['url_redirect' => url()->full()]));
        }

        return $next($request);
    }
}
