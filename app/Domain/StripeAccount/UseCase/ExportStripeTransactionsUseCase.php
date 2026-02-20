<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\ExportStripeTransactionsRequestEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Exports\StripeTransactionsExport;
use App\Domain\Shared\Concerns\RequiresPermission;

class ExportStripeTransactionsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'export-stripe-transactions';
    private StripeAccountRepository $repository;

    public function __construct(StripeAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ExportStripeTransactionsRequestEntity $entity): StripeTransactionsExport
    {
        $this->authorize();

        $transactionsData = $this->repository->getStripeTransactionsForExport($entity);

        return new StripeTransactionsExport($transactionsData);
    }
}
