<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check user profile completion (API version)
 *
 * Uses the default 'api' guard.
 */
class UserLine
{
    /**
     * Handle an incoming request.
     *
     * Supports both email and LINE authentication:
     * - Email users: Skip profile completion check (they have password)
     * - LINE users: Only require profile completion for specific routes if needed
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = \Route::currentRouteName();
        if (auth()->check()) {
            if (in_array($routeName, ['api.profile.index', 'api.profile.update', 'api.logout', 'api.check-email'])) {
                return $next($request);
            }

            /** @var User $user */
            $user = auth()->user();

            // Email authenticated users (have password) skip profile completion check
            // LINE authenticated users also allowed without profile completion
            // This allows both authentication methods to work seamlessly
        }

        return $next($request);
    }
}
