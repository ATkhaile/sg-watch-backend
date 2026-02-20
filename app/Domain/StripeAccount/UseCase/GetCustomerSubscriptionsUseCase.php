<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\CustomerSubscriptionEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetCustomerSubscriptionsUseCase
{
    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(CustomerSubscriptionEntity $entity, int $adminUserId): CustomerSubscriptionEntity
    {
        $data = $this->stripeAccountRepository->getAdminCustomerSubscriptions($entity, $adminUserId);

        $customers = collect($data->items())->map(function ($customer) {
            return [
                'user_id' => $customer->user_id,
                'user_name' => $customer->user_name,
                'user_email' => $customer->user_email,
                'plan_id' => $customer->plan_id,
                'plan_name' => $customer->plan_name,
                'plan_price' => $customer->plan_price,
                'stripe_account_id' => $customer->stripe_account_id,
                'stripe_account_name' => $customer->stripe_account_name,
                'stripe_customer_id' => $customer->stripe_customer_id,
                'subscribed_at' => $customer->subscribed_at,
                'is_activated' => (bool) $customer->is_activated,
                'activated_at' => $customer->activated_at,
                'status' => $customer->is_activated ? __('stripe_accounts.customers.subscriptions.active') : __('stripe_accounts.customers.subscriptions.inactive'),
            ];
        })->toArray();

        $entity->setCustomers($customers);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
