<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Schema;

/**
 * JWT + セッション検証（Web/SPA向け）
 *
 * - JWTトークンによる認証
 * - セッションテーブルによるトークン有効性検証
 */
class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'error' => '認証が間違っています',
                'error_code' => 'AUTH_ERROR',
                'code' => 401,
            ], 401);
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
