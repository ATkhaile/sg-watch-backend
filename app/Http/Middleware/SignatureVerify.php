<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\SignatureInfo;
use App\Enums\SignatureInfoType;
use Carbon\Carbon;

class SignatureVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $timestamp = $request->header('X-Timestamp');
        $appId = $request->header('X-App-Id');
        $signature = $request->header('X-Signature');
        if ($appId) {
            $setting = SignatureInfo::where([
                ['type', SignatureInfoType::APP],
                ['app_id', $appId],
                ['status', 1],
            ])->where(function ($query) {
                $query->where('unlimit_expires', 1)
                    ->orWhere(function ($q) {
                        $q->where('unlimit_expires', 0)
                            ->where('expired_at', '>', Carbon::now()->format('Y-m-d H:i:s'));
                    });
            })->first();
        } else {
            $origin = $request->header('Origin');
            $setting = SignatureInfo::where([
                ['type', SignatureInfoType::WEB],
                ['domain', $origin],
                ['status', 1],
            ])->where(function ($query) {
                $query->where('unlimit_expires', 1)
                    ->orWhere(function ($q) {
                        $q->where('unlimit_expires', 0)
                            ->where('expired_at', '>', Carbon::now()->format('Y-m-d H:i:s'));
                    });
            })->first();
        }
        if (!$setting) {
            return response()->json([
                'error' => 'Invalid signature',
                'error_code' => 'INVALID_SIGNATURE',
                'code' => 403,
            ], 403);
        }
        $expected = base64_encode(
            hash_hmac('sha256', $timestamp, $setting->secret_key, true)
        );
        if (!hash_equals($expected, $signature)) {
            return response()->json([
                'error' => 'Invalid signature',
                'error_code' => 'INVALID_SIGNATURE',
                'code' => 403,
            ], 403);
        }
        if (abs(now()->timestamp - $timestamp) > 300) {
            return response()->json([
                'error' => 'Expired request',
                'error_code' => 'EXPIRED_REQUEST',
                'code' => 403,
            ], 403);
        }

        return $next($request);
    }
}
