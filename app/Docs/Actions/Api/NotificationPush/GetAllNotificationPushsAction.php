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
 *     path="/api/v1/notification_pushs",
 *     summary="Get all notification pushes",
 *     description="Retrieve a paginated list of push notifications with search and sorting options. Requires 'list-notification-pushs' permission.",
 *     operationId="getAllNotificationPushs",
 *     tags={"プッシュ通知"},
 *     security={{"bearerAuth":{}}},
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
 *         @OA\Schema(type="integer", minimum=1, example=10)
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", maxLength=255, description="Search term")
 *     ),
 *     @OA\Parameter(
 *         name="sort",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", description="Column name to sort by")
 *     ),
 *     @OA\Parameter(
 *         name="direction",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC", "asc", "desc"}, description="Sort direction")
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetAllNotificationPushsAction {}
