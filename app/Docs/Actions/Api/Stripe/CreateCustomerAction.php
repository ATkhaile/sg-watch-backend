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
 *     path="/api/v1/stripe/create-customer",
 *     summary="Create Stripe customer",
 *     description="Create a new Stripe customer for the authenticated user. Public endpoint (no authentication required).",
 *     operationId="createStripeCustomer",
 *     tags={"Stripe"},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=422, description="Validation error"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
class CreateCustomerAction {}
