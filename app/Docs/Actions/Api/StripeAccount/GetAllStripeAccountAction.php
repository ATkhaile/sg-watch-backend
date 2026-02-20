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
 *     path="/api/v1/stripe/accounts/list",
 *     summary="Get all Stripe accounts",
 *     description="Retrieve a paginated list of Stripe accounts with search filters and sorting options. Requires 'list-stripe-account' permission.",
 *     operationId="getAllStripeAccounts",
 *     tags={"Stripeアカウント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="search_name",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", maxLength=256, description="Search by account name")
 *     ),
 *     @OA\Parameter(
 *         name="search_user_id",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", description="Filter by user ID")
 *     ),
 *     @OA\Parameter(
 *         name="search_name_like",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="boolean", description="Use LIKE search for name")
 *     ),
 *     @OA\Parameter(
 *         name="search_name_not",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="boolean", description="Negate name search")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=10)
 *     ),
 *     @OA\Parameter(
 *         name="sort_name",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by name")
 *     ),
 *     @OA\Parameter(
 *         name="sort_created",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by creation date")
 *     ),
 *     @OA\Parameter(
 *         name="sort_updated",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by update date")
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetAllStripeAccountAction {}
