<?php

namespace App\Docs\Actions\Api\FcmToken;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="FCMトークン",
 *     description="FCM Token"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/user/{user_id}/fcm_token",
 *     summary="Get user FCM tokens",
 *     description="Retrieve all FCM tokens registered for a specific user.",
 *     operationId="getUserFcmTokens",
 *     tags={"FCMトークン"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetUserFcmTokensAction {}
