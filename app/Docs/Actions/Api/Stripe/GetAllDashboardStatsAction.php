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
 *     path="/api/v1/stripe-account/dashboard-stats/all",
 *     summary="Get all Stripe dashboard stats",
 *     description="Retrieve cached dashboard statistics for all Stripe accounts. Requires authentication.",
 *     operationId="getAllStripeDashboardStats",
 *     tags={"Stripe"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="object")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class GetAllDashboardStatsAction {}
