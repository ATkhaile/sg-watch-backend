<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\StripeAccountEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllStripeAccountFromGuestUseCase
{
    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(StripeAccountEntity $entity): StripeAccountEntity
    {
        $data = $this->stripeAccountRepository->getAllStripeAccount($entity);

        $stripeAccounts = collect($data->items())->map(function ($stripeAccounts) {
            return [
                'stripe_account_id' => $stripeAccounts->stripe_account_id,
                'name' => $stripeAccounts->name,
                'created_at' => $stripeAccounts->created_at,
                'updated_at' => $stripeAccounts->updated_at,
            ];
        })->toArray();

        $entity->setStripeAccount($stripeAccounts);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
