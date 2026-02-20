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
 *     path="/api/v1/notification_pushs",
 *     summary="Create notification push",
 *     description="Create a new push notification. Can send to all users or specific users, immediately or scheduled. Supports redirect to app page, webview, image, or video. Requires 'create-notification-push' permission.",
 *     operationId="createNotificationPush",
 *     tags={"プッシュ通知"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "message", "all_user_flag", "push_now_flag"},
 *                 @OA\Property(property="title", type="string", maxLength=255, example="Important Update"),
 *                 @OA\Property(property="message", type="string", example="Please check the new features"),
 *                 @OA\Property(property="img_path", type="string", format="binary", nullable=true, description="Notification image (jpeg, png, jpg, gif, svg, max 300MB)"),
 *                 @OA\Property(property="all_user_flag", type="boolean", description="Send to all users", example=true),
 *                 @OA\Property(property="push_now_flag", type="boolean", description="Send immediately", example=true),
 *                 @OA\Property(property="sound", type="string", nullable=true, description="iOS system sound (from IOSSystemSound enum)", example="default"),
 *                 @OA\Property(property="push_schedule", type="string", format="date-time", nullable=true, description="Schedule time (required if push_now_flag is false)", example="2025-12-05T10:00:00Z"),
 *                 @OA\Property(property="user_ids", type="array", nullable=true, @OA\Items(type="integer"), description="User IDs (required if all_user_flag is false)", example={1, 2, 3}),
 *                 @OA\Property(property="redirect_type", type="string", nullable=true, description="Redirect type (from RedirectType enum): app_page, webview, image, video", example="app_page"),
 *                 @OA\Property(property="app_page_id", type="integer", nullable=true, description="App page ID (required if redirect_type is app_page)", example=1),
 *                 @OA\Property(property="attach_file", type="string", format="binary", nullable=true, description="Attachment file: image (jpeg, png, jpg, gif, svg) or video (mp4, quicktime, avi, mkv), max 300MB"),
 *                 @OA\Property(property="attach_link", type="string", format="url", maxLength=2048, nullable=true, description="Webview URL (required if redirect_type is webview)", example="https://example.com")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class CreateNotificationPushAction {}
