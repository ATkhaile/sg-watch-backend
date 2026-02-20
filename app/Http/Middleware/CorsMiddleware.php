<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SignatureInfo;
use Carbon\Carbon;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS requests
        if ($request->getMethod() === "OPTIONS") {
            $response = response('', 200);
        } else {
            $response = $next($request);
        }

        // Set CORS headers - Allow multiple origins for development

        $origin = $request->headers->get('Origin');
        $signatureInfo = SignatureInfo::where([
            ['domain', $origin],
            ['status', 1],
        ])->where(function ($query) {
            $query->where('unlimit_expires', 1)
                ->orWhere(function ($q) {
                    $q->where('unlimit_expires', 0)
                        ->where('expired_at', '>', Carbon::now()->format('Y-m-d H:i:s'));
                });
        })->first();
        if ($signatureInfo) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '86400');
        } else {
            // return same error cors
            return response()->json([
                'error' => 'cors domain not found',
                'error_code' => 'CORS_DOMAIN_NOT_FOUND',
                'code' => 403,
            ], 403);
        }

        return $response;
    }
}
