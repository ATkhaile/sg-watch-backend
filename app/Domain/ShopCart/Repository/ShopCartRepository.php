<?php

namespace App\Domain\ShopCart\Repository;

interface ShopCartRepository
{
    public function getCart(?int $userId, ?string $deviceId): array;
    public function addItem(?int $userId, ?string $deviceId, int $productId, int $quantity, string $currency): array;
    public function mergeCarts(string $deviceId, int $userId): void;
}
