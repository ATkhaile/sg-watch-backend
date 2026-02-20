<?php

namespace App\Docs\Actions\Api\NotificationPush;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="プッシュ通知",
 *     description="Push Notification"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/user/receive_notification",
 *     summary="Update receive notification setting",
 *     description="Update notification preferences for a specific FCM token. Controls whether to receive chat notifications and reservation notifications.",
 *     operationId="updateReceiveNotificationSetting",
 *     tags={"プッシュ通知"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"fcm_token"},
 *             @OA\Property(property="fcm_token", type="string", maxLength=255, description="Firebase Cloud Messaging token", example="dGhpcyBpcyBhIGZjbSB0b2tlbiBleGFtcGxl"),
 *             @OA\Property(property="receive_notification_chat", type="boolean", nullable=true, description="Receive chat notifications", example=true),
 *             @OA\Property(property="receive_reservation", type="boolean", nullable=true, description="Receive reservation notifications", example=true)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class UpdateReceiveNotificationSettingAction {}
