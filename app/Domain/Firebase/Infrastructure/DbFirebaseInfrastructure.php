<?php

namespace App\Domain\Firebase\Infrastructure;

use App\Domain\Firebase\Entity\FirebaseNotificationEntity;
use App\Domain\Firebase\Entity\UpdateFirebaseNotificationReadedEntity;
use App\Domain\Firebase\Entity\GetFirebaseUnreadNotificationsEntity;
use App\Domain\Firebase\Repository\FirebaseRepository;
use App\Models\FcmToken;
use App\Models\UserNotification;
use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DbFirebaseInfrastructure implements FirebaseRepository
{
    public function getNotifications(FirebaseNotificationEntity $entity): LengthAwarePaginator
    {
        $fcmToken = FcmToken::where('fcm_token', $entity->getFcmToken())->first();

        if (!$fcmToken) {
            return new LengthAwarePaginator([], 0, $entity->getLimit());
        }

        $query = Notification::select([
            'notifications.*',
            'user_notifications.read_at',
            DB::raw("(SELECT COUNT(*) FROM user_notifications un
                     WHERE un.read_at IS NULL
                     AND un.fcm_token_id = " . $fcmToken->id . ") as notification_unread_count")
        ])
            ->join('user_notifications', 'user_notifications.notification_id', '=', 'notifications.id')
            ->where('user_notifications.fcm_token_id', $fcmToken->id)
            ->orderBy('notifications.created_at', 'DESC');

        if ($entity->getIsRead() === true) {
            $query->whereNotNull('user_notifications.read_at');
        } elseif ($entity->getIsRead() === false) {
            $query->whereNull('user_notifications.read_at');
        }

        return $query->paginate(
            $entity->getLimit(),
            ['*'],
            'page',
            $entity->getPage()
        );
    }

    public function getNotificationDetail(int $notificationId, string $fcmToken): ?array
    {
        $fcmToken = FcmToken::where('fcm_token', $fcmToken)->first();

        if (!$fcmToken) {
            return null;
        }

        $notification = Notification::select([
            'notifications.*',
            'user_notifications.read_at',
        ])
            ->join('user_notifications', 'user_notifications.notification_id', '=', 'notifications.id')
            ->where('notifications.id', $notificationId)
            ->where('user_notifications.fcm_token_id', $fcmToken->id)
            ->first();

        if (!$notification) {
            return null;
        }

        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'content' => $notification->content,
            'push_type' => $notification->push_type,
            'push_datetime' => $notification->push_datetime?->format('Y/m/d H:i:s'),
            'push_now_flag' => $notification->push_now_flag,
            'read_at' => $notification->read_at?->format('Y/m/d H:i:s'),
            'created_at' => $notification->created_at->format('Y/m/d H:i:s'),
            'updated_at' => $notification->updated_at->format('Y/m/d H:i:s'),
        ];
    }

    public function updateNotificationReaded(UpdateFirebaseNotificationReadedEntity $entity, int $id): bool
    {
        $updatedCount = UserNotification::where('notification_id', $id)
            ->whereHas('fcm_token', function ($query) use ($entity) {
                $query->where('fcm_token', $entity->getFcmToken());
            })
            ->update([
                'read_at' => now(),
                'updated_at' => now()
            ]);

        return $updatedCount > 0;
    }

    public function getUnreadNotifications(GetFirebaseUnreadNotificationsEntity $entity): array
    {
        $unreadNotifications = UserNotification::whereHas('fcm_token', function ($query) use ($entity) {
            $query->where('fcm_token', $entity->getFcmToken());
        })
            ->whereNull('read_at')
            ->with('notification')
            ->get()
            ->pluck('notification')
            ->filter()
            ->unique('id')
            ->values()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'content' => $notification->content,
                    'push_type' => $notification->push_type,
                    'push_datetime' => $notification->push_datetime?->format('Y/m/d H:i:s'),
                    'push_now_flag' => $notification->push_now_flag,
                    'created_at' => $notification->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $notification->updated_at->format('Y/m/d H:i:s'),
                ];
            })
            ->toArray();

        return $unreadNotifications;
    }
}
