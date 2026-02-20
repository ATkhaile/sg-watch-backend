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
 *     path="/api/v1/notification_pushs/{id}",
 *     summary="Update notification push",
 *     description="Update an existing push notification. Can update all fields including schedule, recipients, and attachments. Use remove_image and remove_attach_file to delete existing files. Requires 'update-notification-push' permission.",
 *     operationId="updateNotificationPush",
 *     tags={"プッシュ通知"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "message", "all_user_flag", "push_now_flag"},
 *                 @OA\Property(property="title", type="string", maxLength=255, example="Updated Title"),
 *                 @OA\Property(property="message", type="string", example="Updated message"),
 *                 @OA\Property(property="img_path", type="string", format="binary", nullable=true, description="New notification image (jpeg, png, jpg, gif, svg, max 300MB)"),
 *                 @OA\Property(property="all_user_flag", type="boolean", description="Send to all users", example=false),
 *                 @OA\Property(property="push_now_flag", type="boolean", description="Send immediately", example=false),
 *                 @OA\Property(property="sound", type="string", nullable=true, description="iOS system sound (from IOSSystemSound enum)", example="default"),
 *                 @OA\Property(property="push_schedule", type="string", format="date-time", nullable=true, description="Schedule time (required if push_now_flag is false)", example="2025-12-06T10:00:00Z"),
 *                 @OA\Property(property="user_ids", type="array", nullable=true, @OA\Items(type="integer"), description="User IDs (required if all_user_flag is false)", example={4, 5, 6}),
 *                 @OA\Property(property="remove_image", type="boolean", nullable=true, description="Set to true to remove existing notification image", example=false),
 *                 @OA\Property(property="redirect_type", type="string", nullable=true, description="Redirect type (from RedirectType enum)", example="webview"),
 *                 @OA\Property(property="app_page_id", type="integer", nullable=true, description="App page ID (required if redirect_type is app_page)", example=2),
 *                 @OA\Property(property="attach_file", type="string", format="binary", nullable=true, description="New attachment file: image or video, max 300MB"),
 *                 @OA\Property(property="attach_link", type="string", format="url", maxLength=2048, nullable=true, description="Webview URL (required if redirect_type is webview)", example="https://updated.example.com"),
 *                 @OA\Property(property="remove_attach_file", type="boolean", nullable=true, description="Set to true to remove existing attachment file", example=false)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class UpdateNotificationPushAction {}
