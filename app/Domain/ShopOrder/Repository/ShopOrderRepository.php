<?php

namespace App\Domain\ShopOrder\Repository;

interface ShopOrderRepository
{
    public function checkout(int $userId, array $data): array;
    public function getList(int $userId, ?string $status, int $perPage): array;
    public function getDetail(int $userId, int $orderId): ?array;
    public function cancel(int $userId, int $orderId, ?string $reason): array;
    public function adminGetList(array $filters): array;
    public function adminGetDetail(int $orderId): ?array;
    public function adminUpdateStatus(int $orderId, string $status, array $extra = []): array;
    public function handleStripeWebhook(string $payload, string $signature): array;
}
