<?php

namespace App\Docs\Actions\Api\FcmToken;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="FCMトークン",
 *     description="FCM Token"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/fcm_token/{fcm_token_id}/status",
 *     summary="Update FCM token status",
 *     description="Update the active status of an FCM token. Status must be from ActiveStatus enum.",
 *     operationId="updateFcmTokenStatus",
 *     tags={"FCMトークン"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="fcm_token_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"active_status"},
 *             @OA\Property(property="active_status", type="string", description="Active status (from ActiveStatus enum)", example="active")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class UpdateFcmTokenStatusAction {}
