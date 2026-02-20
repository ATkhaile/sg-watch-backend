<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * JWTのみ（Mobile向け）
 *
 * - JWTトークンによる認証のみ
 * - セッション検証なし（モバイルアプリ用のシンプルな認証）
 */
class AuthBasic
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

        return $next($request);
    }
}
