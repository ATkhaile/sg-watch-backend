<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class AdminUpdatePaymentStatusUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $orderId, string $paymentStatus): array
    {
        return $this->repository->adminUpdatePaymentStatus($orderId, $paymentStatus);
    }
}
