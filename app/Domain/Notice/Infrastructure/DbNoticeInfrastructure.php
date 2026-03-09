<?php

namespace App\Domain\Notice\Infrastructure;

use App\Domain\Notice\Repository\NoticeRepository;
use App\Enums\ActiveStatus;
use App\Models\FcmToken;
use App\Models\Notice;
use App\Models\UserNotice;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class DbNoticeInfrastructure implements NoticeRepository
{
    public function getList(array $filters): array
    {
        $query = Notice::query();

        if (isset($filters['keyword']) && $filters['keyword'] !== '') {
            $keyword = '%' . $filters['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('content', 'like', $keyword);
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'notices' => collect($paginator->items())->map(fn($notice) => $this->formatNotice($notice))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getById(int $id): ?array
    {
        $notice = Notice::find($id);
        if (!$notice) {
            return null;
        }
        return $this->formatNotice($notice);
    }

    public function create(array $data): array
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        if ($image instanceof UploadedFile) {
            $path = $image->store('notices', 'public');
            $data['image_url'] = $path;
        }

        $notice = Notice::create($data);

        // Send Firebase push notification to all active users
        if ($notice->is_active) {
            $this->sendNoticePush($notice);
        }

        return [
            'success' => true,
            'message' => 'Notice created successfully.',
            'notice' => $this->formatNotice($notice),
        ];
    }

    public function update(int $id, array $data): array
    {
        $notice = Notice::find($id);
        if (!$notice) {
            return ['success' => false, 'message' => 'Notice not found.'];
        }

        $image = $data['image'] ?? null;
        unset($data['image']);

        $notice->update($data);

        if ($image instanceof UploadedFile) {
            if ($notice->image_url && Storage::disk('public')->exists($notice->image_url)) {
                Storage::disk('public')->delete($notice->image_url);
            }
            $path = $image->store('notices/' . $notice->id, 'public');
            $notice->update(['image_url' => $path]);
        }

        return [
            'success' => true,
            'message' => 'Notice updated successfully.',
            'notice' => $this->formatNotice($notice->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $notice = Notice::find($id);
        if (!$notice) {
            return ['success' => false, 'message' => 'Notice not found.', 'status_code' => 404];
        }

        $notice->delete();

        return ['success' => true, 'message' => 'Notice deleted successfully.', 'status_code' => 200];
    }

    public function getMemberNotices(int $userId, array $filters): array
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;

        // Get active system notices
        $systemNotices = Notice::where('is_active', true)
            ->select('id', 'title', 'content', 'image_url', 'created_at')
            ->get()
            ->map(fn($notice) => [
                'id' => 'system_' . $notice->id,
                'type' => 'system',
                'title' => $notice->title,
                'content' => $notice->content,
                'image_url' => $notice->image_full_url,
                'data' => null,
                'read_at' => null,
                'created_at' => $notice->created_at?->toIso8601String(),
            ]);

        // Get user's personal notices
        $userNotices = UserNotice::where('user_id', $userId)
            ->select('id', 'type', 'title', 'content', 'data', 'read_at', 'created_at')
            ->get()
            ->map(fn($notice) => [
                'id' => 'user_' . $notice->id,
                'type' => $notice->type,
                'title' => $notice->title,
                'content' => $notice->content,
                'data' => $notice->data,
                'read_at' => $notice->read_at?->toIso8601String(),
                'created_at' => $notice->created_at?->toIso8601String(),
            ]);

        // Merge and sort by created_at desc
        $merged = $systemNotices->merge($userNotices)
            ->sortByDesc('created_at')
            ->values();

        $total = $merged->count();
        $lastPage = (int) ceil($total / $perPage);
        $items = $merged->forPage($page, $perPage)->values()->toArray();

        return [
            'notices' => $items,
            'pagination' => [
                'current_page' => (int) $page,
                'last_page' => $lastPage,
                'per_page' => (int) $perPage,
                'total' => $total,
            ],
        ];
    }

    public function markAsRead(int $userId, int $userNoticeId): array
    {
        $notice = UserNotice::where('id', $userNoticeId)
            ->where('user_id', $userId)
            ->first();

        if (!$notice) {
            return ['success' => false, 'message' => 'Notice not found.', 'status_code' => 404];
        }

        if ($notice->read_at) {
            return ['success' => true, 'message' => 'Notice already read.', 'status_code' => 200];
        }

        $notice->update(['read_at' => now()]);

        return ['success' => true, 'message' => 'Notice marked as read.', 'status_code' => 200];
    }

    private function sendNoticePush(Notice $notice): void
    {
        try {
            $credentialsPath = base_path(config('services.firebase.credentials_path'));
            if (!$credentialsPath || !file_exists($credentialsPath)) {
                Log::channel('log_notification_push')->error('Firebase credentials file not found', [
                    'path' => config('services.firebase.credentials_path'),
                ]);
                return;
            }

            $fcmTokens = FcmToken::where('active_status', ActiveStatus::ACTIVE)
                ->whereNotNull('user_id')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($fcmTokens)) {
                return;
            }

            $messaging = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->createMessaging();

            $notification = Notification::create($notice->title, $notice->content);

            $data = [
                'type' => 'notice',
                'notice_id' => (string) $notice->id,
            ];

            foreach ($fcmTokens as $token) {
                try {
                    $message = CloudMessage::withTarget('token', $token)
                        ->withNotification($notification)
                        ->withData($data);

                    $messaging->send($message);
                } catch (\Throwable $e) {
                    Log::channel('log_notification_push')->error('Firebase push failed for token (notice)', [
                        'notice_id' => $notice->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::channel('log_notification_push')->error('Firebase notice push failed', [
                'notice_id' => $notice->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function formatNotice(Notice $notice): array
    {
        return [
            'id' => $notice->id,
            'title' => $notice->title,
            'content' => $notice->content,
            'image_url' => $notice->image_full_url,
            'is_active' => $notice->is_active,
            'created_at' => $notice->created_at?->toIso8601String(),
            'updated_at' => $notice->updated_at?->toIso8601String(),
        ];
    }
}
