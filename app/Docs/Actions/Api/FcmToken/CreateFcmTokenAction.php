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
 *     path="/api/v1/fcm_token",
 *     summary="Register FCM token",
 *     description="Register a Firebase Cloud Messaging (FCM) token for push notifications.",
 *     operationId="createFcmToken",
 *     tags={"FCMトークン"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"fcm_token"},
 *             @OA\Property(property="fcm_token", type="string", maxLength=255, description="FCM token from device", example="fGh1jK2lM3nO4pQ5rS6tU7vW8xY9zA0bC"),
 *             @OA\Property(property="device_name", type="string", maxLength=255, nullable=true, description="Device name", example="iPhone 13 Pro"),
 *             @OA\Property(property="app_version_name", type="string", nullable=true, description="App version name (must exist in app_versions table)", example="1.0.0")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class CreateFcmTokenAction {}
