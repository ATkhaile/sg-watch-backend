<?php

namespace App\Docs\Actions\Api\StripeAccount;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripeアカウント",
 *     description="Stripe Account"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/stripe/accounts/{stripe_account_id}/transactions/export",
 *     summary="Export Stripe transactions",
 *     description="Export transactions (charges) from Stripe API for a specific account to CSV or other format. Public endpoint (no authentication required).",
 *     operationId="exportStripeTransactions",
 *     tags={"Stripeアカウント"},
 *     @OA\Parameter(
 *         name="stripe_account_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, maximum=10000, example=1000)
 *     ),
 *     @OA\Parameter(
 *         name="start_date",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2025-01-01")
 *     ),
 *     @OA\Parameter(
 *         name="end_date",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2025-12-31")
 *     ),
 *     @OA\Response(response=200, description="Success - File download"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class ExportStripeTransactionsAction {}
