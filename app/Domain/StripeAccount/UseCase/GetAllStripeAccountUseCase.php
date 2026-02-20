<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\StripeAccount\Entity\StripeAccountEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllStripeAccountUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-stripe-account';

    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(StripeAccountEntity $entity): StripeAccountEntity
    {
        $this->authorize();

        $data = $this->stripeAccountRepository->getAllStripeAccount($entity);

        $stripeAccounts = collect($data->items())->map(function ($stripeAccount) {
            return [
                'id' => $stripeAccount->id,
                'stripe_account_id' => $stripeAccount->stripe_account_id,
                'display_name' => $stripeAccount->display_name,
                'status' => $stripeAccount->status,
                'account_id' => $stripeAccount->account_id,
                'account_name' => $stripeAccount->account_name,
                'country' => $stripeAccount->country,
                'currency' => $stripeAccount->currency,
                'charges_enabled' => $stripeAccount->charges_enabled,
                'payouts_enabled' => $stripeAccount->payouts_enabled,
                'business_type' => $stripeAccount->business_type,
                'is_test_mode' => $stripeAccount->is_test_mode,
                'public_key' => $stripeAccount->public_key,
                'secret_key' => $stripeAccount->secret_key,
                'webhook_secret' => $stripeAccount->webhook_secret,
                'payment_link' => $stripeAccount->payment_link,
                'last_connected_at' => $stripeAccount->last_connected_at,
                'last_synced_at' => $stripeAccount->last_synced_at,
                'creator_id' => $stripeAccount->creator_id,
                'updator_id' => $stripeAccount->updator_id,
                'created_at' => $stripeAccount->created_at,
                'updated_at' => $stripeAccount->updated_at,
            ];
        })->toArray();

        $entity->setStripeAccount($stripeAccounts);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
