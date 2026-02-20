<?php

namespace App\Docs\Actions\Api\Firebase;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Firebase",
 *     description="Firebase"
 * )
 *
 * @OA\Put(
 *     path="/api/v1/firebase/notifications/{id}/readed",
 *     summary="Mark Firebase notification as read",
 *     description="Mark a specific Firebase notification as read for a given FCM token.",
 *     operationId="updateFirebaseNotificationReaded",
 *     tags={"Firebase"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"fcm_token"},
 *             @OA\Property(property="fcm_token", type="string", maxLength=255, description="FCM token", example="fGh1jK2lM3nO4pQ5rS6tU7vW8xY9zA0bC")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class UpdateFirebaseNotificationReadedAction {}
