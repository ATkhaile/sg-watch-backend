<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\GetStripePricesRequestEntity;
use App\Domain\StripeAccount\Entity\StripePriceEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Domain\Shared\Concerns\RequiresPermission;

class GetStripePricesUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-stripe-products';
    private StripeAccountRepository $stripeAccountRepository;

    public function __construct(StripeAccountRepository $stripeAccountRepository)
    {
        $this->stripeAccountRepository = $stripeAccountRepository;
    }

    public function __invoke(GetStripePricesRequestEntity $requestEntity): StripePriceEntity
    {
        $this->authorize();

        return $this->stripeAccountRepository->getStripePrices($requestEntity);
    }
}
