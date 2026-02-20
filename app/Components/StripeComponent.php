<?php

namespace App\Components;

use App\Models\Stripe\StripeAccount;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeComponent
{
    /**
     * Get the Stripe secret key from stripe_accounts table
     *
     * @throws \InvalidArgumentException when stripeAccountId is not provided or account not found
     */
    private static function getSecretKey(?int $stripeAccountId = null): string
    {
        if (!$stripeAccountId) {
            throw new \InvalidArgumentException('stripeAccountId is required');
        }

        $stripeAccount = StripeAccount::find($stripeAccountId);
        if (!$stripeAccount || !$stripeAccount->secret_key) {
            throw new \InvalidArgumentException('Stripe account not found or secret_key is not set');
        }

        return $stripeAccount->secret_key;
    }

    /**
     * Get the Stripe public key from stripe_accounts table
     *
     * @throws \InvalidArgumentException when stripeAccountId is not provided or account not found
     */
    public static function getPublicKey(?int $stripeAccountId = null): string
    {
        if (!$stripeAccountId) {
            throw new \InvalidArgumentException('stripeAccountId is required');
        }

        $stripeAccount = StripeAccount::find($stripeAccountId);
        if (!$stripeAccount || !$stripeAccount->public_key) {
            throw new \InvalidArgumentException('Stripe account not found or public_key is not set');
        }

        return $stripeAccount->public_key;
    }

    public static function createCustomer($email, $name, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createCustomer',
            'data' => [
                'email' => $email,
                'name' => $name,
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->create([
                'email' => $email,
                'name' => $name,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createCustomer',
                'message' => $th->getMessage(),
            ]);
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }
    public static function updateCustomer($id, $data, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'update customer meta',
            'data' => [
                'id' => $id,
                'data' => $data,
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->update($id, $data);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'updateCustomerMeta',
                'message' => $th->getMessage(),
            ]);
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function getCustomer($customerId, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->retrieve($customerId);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function getChargesRetrieve($chargeId, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->charges->retrieve(
                $chargeId,
                ['expand' => ['customer', 'invoice.subscription']]
            );
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function createCharge($invoice, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            $ret = $stripe->charges->create([
                'amount' => $invoice->total_price,
                'customer' => $invoice->user->customer_id,
                'currency' => 'jpy',
                'description' => 'Payment daily month ',
            ]);
            Log::channel('log_stripe')->info('Stripe return:'.$ret);
            if ($ret->status != 'succeeded') {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function getDefaultSourceCard($customerId, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));

        return $stripe->customers->retrieve(
            $customerId,
            []
        )->default_source;
    }

    public static function getCustomerCard($customerId, $cardId, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->retrieveSource($customerId, $cardId);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function addCustomerCard($customerId, $tokenCard, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'addCustomerCard',
            'data' => ['customerId' => $customerId, 'tokenCard' => $tokenCard],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->createSource(
                $customerId,
                ['source' => $tokenCard]
            );
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'addCustomerCard',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function deleteCustomer($customerId, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->delete(
                $customerId
            );
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function addCustomerCardDefault($customerId, $tokenCard, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'addCustomerCardDefault',
            'data' => ['customerId' => $customerId, 'tokenCard' => $tokenCard],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->update(
                $customerId,
                ['default_source' => $tokenCard]
            );
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function createTokenCard($request, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->tokens->create([
                'card' => [
                    'number' => $request->credit_card,
                    'exp_month' => $request->expire_month,
                    'exp_year' => $request->expire_year,
                    'cvc' => $request->security_code,
                ],
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function getTokenCard(?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->tokens->retrieve('tok_visa');
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());
            return false;
        }
    }

    public static function createProduct($name, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'create product',
            'data' => ['name' => $name],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->products->create([
                'name' => $name,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'create product',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createPrice($productId, $amount, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createPrice',
            'data' => ['amount' => $amount],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->prices->create([
                'unit_amount' => $amount,
                'currency' => 'jpy',
                'product_data' => [
                    'name' => 'plan id '.$productId,
                ],
                // 'recurring' => ['interval' => 'month'],
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createPrice',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function updatePrice($priceId, $amount, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createPrice',
            'data' => ['priceId' => $priceId, 'amount' => $amount],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->prices->update($priceId, [
                'unit_amount' => $amount,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'updatePrice',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createSubscription($data, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createSubscription',
            'data' => $data,
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->subscriptions->create($data);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createSubscription',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function updateSubscription($subcriptionId, $priceId, $trialEnd, $cancelAt, $defaultPayment, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'updateSubscription',
            'data' => [
                'subcriptionId' => $subcriptionId,
                'trialEnd' => $trialEnd,
                'cancelAt' => $cancelAt,
                'items' => [
                    ['price' => $priceId],
                ],
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->subscriptions->update($subcriptionId, [
                'trial_end' => $trialEnd,
                'cancel_at' => $cancelAt,
                'default_payment_method' => $defaultPayment,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'updateSubscription',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function updateSubscriptionTrailEnd($subcriptionId, $trialEnd, $cancelAt, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'updateSubscriptionTrailEnd',
            'data' => [
                'subcriptionId' => $subcriptionId,
                'trialEnd' => $trialEnd,
                'cancelAt' => $cancelAt,
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->subscriptions->update($subcriptionId, [
                'trial_end' => $trialEnd,
                'cancel_at' => $cancelAt,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'updateSubscriptionTrailEnd',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function cancelSubscription($subcriptionId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'cancelSubscription',
            'data' => [
                'subcriptionId' => $subcriptionId,
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->subscriptions->cancel($subcriptionId, [
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'cancelSubscription',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createSubscriptionSchedule($customerId, $priceId, $startDate, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createSubscription',
            'data' => [
                'customerId' => $customerId,
                'priceId' => $priceId,
                'startDate' => $startDate,
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->subscriptionSchedules->create([
                'customer' => $customerId,
                'start_date' => $startDate,
                // 'end_date' => $endDate,
                'end_behavior' => 'release',
                'phases' => [
                    [
                        'items' => [
                            [
                                'price' => $priceId,
                                'quantity' => 1,
                            ],
                        ],
                        'iterations' => 1,
                    ],
                ],
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createSubscription',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createPaymentIntents($data, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createpPaymentIntents',
            'data' => $data,
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->paymentIntents->create($data);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createpPaymentIntents',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function capturePaymentIntents($id, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'capturePaymentIntents',
            'data' => $id,
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->paymentIntents->capture($id);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'capturePaymentIntents',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function cancelPaymentIntents($id, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'cancelPaymentIntents',
            'data' => $id,
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->paymentIntents->cancel($id);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'cancelPaymentIntents',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function retrieveSource($customerId, $cardId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'retrieveSource',
            'data' => [
                'customerId' => $customerId,
                'cardId' => $cardId,
            ],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->customers->retrieveSource($customerId, $cardId, []);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'retrieveSource',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function refund($data, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'refund',
            'data' => $data,
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->refunds->create($data);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'refund',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createPaymentIntent3D($data, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createpPaymentIntents',
            'data' => $data,
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->paymentIntents->create($data);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createpPaymentIntents',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function getPaymentIntent($id, ?int $stripeAccountId = null)
    {
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->paymentIntents->retrieve($id);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error($th->getMessage());

            return false;
        }
    }

    public static function createInvoiceItem($customerId, $priceId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createInvoiceItem',
            'data' => ['customerId' => $customerId, 'priceId' => $priceId],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->invoiceItems->create([
                'customer' => $customerId,
                'price' => $priceId,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createInvoiceItem',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createPreviewInvoice($customerId, $invoiceId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createPreviewInvoice',
            'data' => ['customerId' => $customerId, 'invoiceId' => $invoiceId],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->invoices->createPreview([
                'customer' => $customerId,
                'invoice_items' => [
                    ['invoiceitem' => $invoiceId],
                ]
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createPreviewInvoice',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function createInvoice($customerId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'createInvoice',
            'data' => ['customerId' => $customerId],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->invoices->create([
                'from_invoice' => [
                    'customer' => $customerId
                ],
                // 'metadata' => ['xxx' => 'yyy']
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'createInvoice',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }

    public static function retrieveInvoice($invoiceId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'retrieveInvoice',
            'data' => ['invoiceId' => $invoiceId],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->invoices->finalizeInvoice($invoiceId);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'retrieveInvoice',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }
    public static function payInvoice($invoiceId, $cardId, ?int $stripeAccountId = null)
    {
        Log::channel('log_stripe')->info([
            'api' => 'payInvoice',
            'data' => ['invoiceId' => $invoiceId],
        ]);
        $stripe = new StripeClient(self::getSecretKey($stripeAccountId));
        try {
            return $stripe->invoices->pay($invoiceId, [
                'payment_method' => $cardId,
            ]);
        } catch (\Throwable $th) {
            Log::channel('log_stripe')->error([
                'api' => 'payInvoice',
                'message' => $th->getMessage(),
            ]);

            return false;
        }
    }
}
