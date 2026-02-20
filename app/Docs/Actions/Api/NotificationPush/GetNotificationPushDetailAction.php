<?php

namespace App\Docs\Actions\Api\NotificationPush;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="プッシュ通知",
 *     description="Push Notification"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/notification_pushs/{id}",
 *     summary="Get notification push detail",
 *     description="Retrieve details of a specific push notification. Requires 'view-notification-push' permission.",
 *     operationId="getNotificationPushDetail",
 *     tags={"プッシュ通知"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
class GetNotificationPushDetailAction {}
