<?php

namespace App\Docs\Actions\Api\Stripe;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripe",
 *     description="Stripe"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/stripe-account/{stripe_account_id}/dashboard-stats",
 *     summary="Get Stripe dashboard stats",
 *     description="Retrieve cached dashboard statistics for a specific Stripe account. Returns 404 if stats not found (needs refresh). Requires authentication.",
 *     operationId="getStripeDashboardStats",
 *     tags={"Stripe"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="stripe_account_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=404, description="Dashboard stats not found. Please refresh to fetch data.")
 * )
 */
class GetDashboardStatsAction {}
