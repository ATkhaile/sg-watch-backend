<?php

namespace App\Domain\ShopCart\UseCase;

use App\Domain\ShopCart\Repository\ShopCartRepository;

final class AddToCartUseCase
{
    private ShopCartRepository $repository;

    public function __construct(ShopCartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(?int $userId, ?string $deviceId, int $productId, int $quantity, string $currency): array
    {
        return $this->repository->addItem($userId, $deviceId, $productId, $quantity, $currency);
    }
}
