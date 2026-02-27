<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;
use Illuminate\Http\UploadedFile;

final class UpdatePaymentReceiptUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $orderId, UploadedFile $paymentReceipt): array
    {
        return $this->repository->updatePaymentReceipt($userId, $orderId, $paymentReceipt);
    }
}
