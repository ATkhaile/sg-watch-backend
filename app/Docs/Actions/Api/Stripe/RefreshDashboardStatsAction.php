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
 *     path="/api/v1/stripe-account/{stripe_account_id}/dashboard-stats/refresh",
 *     summary="Refresh Stripe dashboard stats",
 *     description="Fetch and refresh dashboard statistics from Stripe API for a specific Stripe account. Requires authentication.",
 *     operationId="refreshStripeDashboardStats",
 *     tags={"Stripe"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="stripe_account_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Dashboard stats refreshed successfully"),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=500, description="Failed to refresh dashboard stats")
 * )
 */
class RefreshDashboardStatsAction {}
