<?php

namespace App\Domain\Notice\Infrastructure;

use App\Domain\Notice\Repository\NoticeRepository;
use App\Models\Notice;
use App\Models\UserNotice;

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
        $notice = Notice::create($data);

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

        $notice->update($data);

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
            ->select('id', 'title', 'content', 'created_at')
            ->get()
            ->map(fn($notice) => [
                'id' => 'system_' . $notice->id,
                'type' => 'system',
                'title' => $notice->title,
                'content' => $notice->content,
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

    private function formatNotice(Notice $notice): array
    {
        return [
            'id' => $notice->id,
            'title' => $notice->title,
            'content' => $notice->content,
            'is_active' => $notice->is_active,
            'created_at' => $notice->created_at?->toIso8601String(),
            'updated_at' => $notice->updated_at?->toIso8601String(),
        ];
    }
}
