<?php

namespace App\Docs\Actions\Api\Firebase;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Firebase",
 *     description="Firebase"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/firebase/notifications",
 *     summary="Get Firebase notifications",
 *     description="Retrieve paginated list of Firebase notifications for a specific FCM token.",
 *     operationId="getFirebaseNotifications",
 *     tags={"Firebase"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="fcm_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", maxLength=255, description="FCM token to get notifications for")
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
 *         @OA\Schema(type="integer", minimum=1, example=20)
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetFirebaseNotificationsAction {}
