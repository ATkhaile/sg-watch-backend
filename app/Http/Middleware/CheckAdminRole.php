<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;
use Illuminate\Support\Facades\Schema;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('api')->guest()) {
            return response()->json([
                'error' => '認証が間違っています',
                'error_code' => 'AUTH_ERROR',
                'code' => 401,
            ], 401);
        }

        $user = Auth::guard('api')->user();

        // Check if user has admin role through users_roles table
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'error' => 'アクセス権限がありません',
                'error_code' => 'ACCESS_DENIED',
                'code' => 403,
            ], 403);
        }

        if (Schema::hasTable('sessions')) {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'error' => 'トークンが見つかりません',
                    'error_code' => 'TOKEN_NOT_FOUND',
                    'code' => 401,
                ], 401);
            }

            $tokenHash = hash('sha256', $token);

            $session = Session::where('token_hash', $tokenHash)
                ->where('is_active', 1)
                ->first();

            if (!$session) {
                return response()->json([
                    'error' => '無効なトークンです',
                    'error_code' => 'INVALID_TOKEN',
                    'code' => 401,
                ], 401);
            }
        }

        return $next($request);
    }
}
