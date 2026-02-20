<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\GetStripeProductsRequestEntity;
use App\Domain\StripeAccount\Entity\StripeProductEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Domain\Shared\Concerns\RequiresPermission;

class GetStripeProductsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-stripe-products';
    private StripeAccountRepository $stripeAccountRepository;

    public function __construct(StripeAccountRepository $stripeAccountRepository)
    {
        $this->stripeAccountRepository = $stripeAccountRepository;
    }

    public function __invoke(GetStripeProductsRequestEntity $requestEntity): StripeProductEntity
    {
        $this->authorize();

        return $this->stripeAccountRepository->getStripeProducts($requestEntity);
    }
}
