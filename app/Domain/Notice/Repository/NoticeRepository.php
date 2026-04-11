<?php

namespace App\Domain\Notice\Repository;

interface NoticeRepository
{
    public function getList(array $filters): array;

    public function getById(int $id): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): array;

    public function delete(int $id): array;

    public function getMemberNotices(int $userId, array $filters): array;

    public function getMemberNoticeDetail(int $userId, string $noticeId): ?array;

    public function markAsRead(int $userId, int $userNoticeId): array;
}
