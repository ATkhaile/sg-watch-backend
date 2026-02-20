<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\StripeAccountEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllStripeAccountFromCustomerUseCase
{
    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(StripeAccountEntity $entity): StripeAccountEntity
    {
        $data = $this->stripeAccountRepository->getAllStripeAccount($entity);
        $activeUserStripeAccountId = $entity->getActiveUserStripeAccountId();

        $stripeAccounts = collect($data->items())->map(function ($stripeAccount) use ($activeUserStripeAccountId) {
            return [
                'stripe_account_id' => $stripeAccount->stripe_account_id,
                'name' => $stripeAccount->name,
                'created_at' => $stripeAccount->created_at,
                'updated_at' => $stripeAccount->updated_at,
                'active' => $stripeAccount->stripe_account_id === $activeUserStripeAccountId,
            ];
        })->toArray();

        $entity->setStripeAccount($stripeAccounts);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
