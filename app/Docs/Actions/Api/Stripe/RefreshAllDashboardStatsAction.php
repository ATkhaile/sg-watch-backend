<?php

namespace App\Docs\Actions\Api\Stripe;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripe",
 *     description="Stripe"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/stripe-account/dashboard-stats/refresh-all",
 *     summary="Refresh all Stripe dashboard stats",
 *     description="Fetch and refresh dashboard statistics from Stripe API for all Stripe accounts. Requires authentication.",
 *     operationId="refreshAllStripeDashboardStats",
 *     tags={"Stripe"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="All dashboard stats refreshed successfully"),
 *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=500, description="Failed to refresh all dashboard stats")
 * )
 */
class RefreshAllDashboardStatsAction {}
