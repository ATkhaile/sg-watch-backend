<?php

namespace App\Domain\ShopOrder\Repository;

interface ShopOrderRepository
{
    public function checkout(int $userId, array $data): array;
    public function getList(int $userId, ?string $status, int $perPage, ?bool $isNew = null): array;
    public function getDetail(int $userId, int $orderId): ?array;
    public function cancel(int $userId, int $orderId, ?string $reason): array;
    public function updatePaymentReceipt(int $userId, int $orderId, \Illuminate\Http\UploadedFile $paymentReceipt): array;
    public function adminGetList(array $filters): array;
    public function adminGetDetail(int $orderId): ?array;
    public function adminUpdateStatus(int $orderId, string $status, array $extra = []): array;
    public function adminUpdatePaymentStatus(int $orderId, string $paymentStatus): array;
    public function adminCreateOrder(array $data): array;
    public function adminUpdateOrder(int $orderId, array $data): array;
    public function handleStripeWebhook(string $payload, string $signature): array;
}
