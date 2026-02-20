<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\GetStripeTransactionsRequestEntity;
use App\Domain\StripeAccount\Entity\StripeTransactionEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Domain\Shared\Concerns\RequiresPermission;

class GetStripeTransactionsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-stripe-transactions';
    private StripeAccountRepository $stripeAccountRepository;

    public function __construct(StripeAccountRepository $stripeAccountRepository)
    {
        $this->stripeAccountRepository = $stripeAccountRepository;
    }

    public function __invoke(GetStripeTransactionsRequestEntity $requestEntity): StripeTransactionEntity
    {
        $this->authorize();

        return $this->stripeAccountRepository->getStripeTransactions($requestEntity);
    }
}
