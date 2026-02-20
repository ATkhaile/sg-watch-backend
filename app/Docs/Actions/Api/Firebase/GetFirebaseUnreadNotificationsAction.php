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
 *     path="/api/v1/firebase/notifications/unread",
 *     summary="Get unread Firebase notifications",
 *     description="Retrieve list of unread Firebase notifications for a specific FCM token.",
 *     operationId="getFirebaseUnreadNotifications",
 *     tags={"Firebase"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="fcm_token",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", maxLength=255, description="FCM token to get unread notifications for")
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetFirebaseUnreadNotificationsAction {}
