<?php

namespace App\Domain\ShopOrder\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopOrder\Repository\ShopOrderRepository;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ShippingMethod;
use App\Models\Shop\Cart;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Stripe\StripeClient;

class DbShopOrderInfrastructure implements ShopOrderRepository
{
    public function checkout(int $userId, array $data): array
    {
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart || $cart->items()->count() === 0) {
            return ['success' => false, 'message' => 'Cart is empty.'];
        }

        $cart->load(['items.product.images']);

        $address = UserAddress::where('id', $data['address_id'])
            ->where('user_id', $userId)
            ->first();

        if (!$address) {
            return ['success' => false, 'message' => 'Address not found.'];
        }

        $address->load(['jpDetail', 'vnDetail']);

        $currency = $data['currency'] ?? 'VND';

        // Validate stock and calculate subtotal
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $product = $item->product;

            if (!$product || !$product->is_active) {
                return ['success' => false, 'message' => "Product \"{$item->product_id}\" is no longer available."];
            }

            if ($product->stock_quantity < $item->quantity) {
                return ['success' => false, 'message' => "Insufficient stock for \"{$product->name}\". Available: {$product->stock_quantity}"];
            }

            $price = $currency === 'JPY' ? $product->price_jpy : $product->price_vnd;
            $subtotal += $price * $item->quantity;
        }

        $shippingFee = $this->calculateShippingFee($data['shipping_method'], $currency);
        $codFee = $this->calculateCodFee($data['payment_method'], $currency);
        $depositAmount = $this->calculateDepositAmount($data['payment_method'], $currency);
        $totalAmount = $subtotal + $shippingFee + $codFee;

        $shippingInfo = $this->buildShippingInfo($address, $userId);

        // Upload payment receipt
        $receiptPath = null;
        if (isset($data['payment_receipt']) && $data['payment_receipt'] instanceof UploadedFile) {
            $receiptPath = $data['payment_receipt']->store('orders/receipts/' . $userId, 'public');
        }

        return DB::transaction(function () use ($userId, $data, $cart, $currency, $subtotal, $shippingFee, $codFee, $depositAmount, $totalAmount, $shippingInfo, $receiptPath) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'status' => OrderStatus::PENDING,
                'payment_status' => PaymentStatus::PENDING,
                'payment_method' => $data['payment_method'],
                'payment_receipt' => $receiptPath,
                'shipping_method' => $data['shipping_method'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'cod_fee' => $codFee,
                'deposit_amount' => $depositAmount,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'currency' => $currency,
                'shipping_name' => $shippingInfo['name'],
                'shipping_phone' => $shippingInfo['phone'],
                'shipping_address' => $shippingInfo['address'],
                'shipping_city' => $shippingInfo['city'],
                'shipping_country' => $shippingInfo['country'],
                'shipping_postal_code' => $shippingInfo['postal_code'],
                'note' => $data['note'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $product = $item->product;
                $price = $currency === 'JPY' ? $product->price_jpy : $product->price_vnd;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $product->primary_image_url,
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $item->quantity,
                ]);

                Product::where('id', $product->id)
                    ->decrement('stock_quantity', $item->quantity);
            }

            $cart->items()->delete();
            $cart->delete();

            // Create Stripe PaymentIntent if payment method is stripe
            $stripeClientSecret = null;
            if ($data['payment_method'] === PaymentMethod::STRIPE) {
                $stripeResult = $this->createStripePaymentIntent($order, $totalAmount, $currency);
                if (!$stripeResult['success']) {
                    throw new \RuntimeException($stripeResult['message']);
                }
                $order->update(['stripe_payment_intent_id' => $stripeResult['payment_intent_id']]);
                $stripeClientSecret = $stripeResult['client_secret'];
            }

            $order->load('items');

            $response = [
                'success' => true,
                'message' => 'Order placed successfully.',
                'order' => $this->formatOrder($order),
            ];

            if ($stripeClientSecret) {
                $response['stripe_client_secret'] = $stripeClientSecret;
                $response['stripe_public_key'] = config('services.stripe.public_key');
            }

            return $response;
        });
    }

    public function getList(int $userId, ?string $status, int $perPage): array
    {
        $query = Order::where('user_id', $userId)
            ->with('items')
            ->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        $paginator = $query->paginate($perPage);

        return [
            'orders' => collect($paginator->items())->map(fn (Order $order) => $this->formatOrderSummary($order))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getDetail(int $userId, int $orderId): ?array
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->with('items')
            ->first();

        if (!$order) {
            return null;
        }

        return $this->formatOrder($order);
    }

    public function cancel(int $userId, int $orderId, ?string $reason): array
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        if (!in_array($order->status, [OrderStatus::PENDING, OrderStatus::CONFIRMED])) {
            return ['success' => false, 'message' => 'Order cannot be cancelled in current status.'];
        }

        return DB::transaction(function () use ($order, $reason) {
            // Restore stock
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)
                        ->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update([
                'status' => OrderStatus::CANCELLED,
                'cancel_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'order' => $this->formatOrder($order->fresh('items')),
            ];
        });
    }

    public function adminGetList(array $filters): array
    {
        $query = Order::query()
            ->with(['items', 'user:id,first_name,last_name,email'])
            ->orderByDesc('created_at');

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by payment_status
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        // Filter by payment_method
        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        // Search by order_number or user name/email
        if (!empty($filters['keyword'])) {
            $keyword = '%' . $filters['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('order_number', 'like', $keyword)
                  ->orWhereHas('user', function ($uq) use ($keyword) {
                      $uq->where('first_name', 'like', $keyword)
                        ->orWhere('last_name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword);
                  });
            });
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'newest';
        match ($sortBy) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'total_asc' => $query->orderBy('total_amount', 'asc'),
            'total_desc' => $query->orderBy('total_amount', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'orders' => collect($paginator->items())->map(fn (Order $order) => $this->formatAdminOrderSummary($order))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function adminGetDetail(int $orderId): ?array
    {
        $order = Order::with(['items', 'user:id,uuid,first_name,last_name,email'])
            ->find($orderId);

        if (!$order) {
            return null;
        }

        $data = $this->formatOrder($order);
        $data['admin_note'] = $order->admin_note;
        $data['user'] = $order->user ? [
            'id' => $order->user->id,
            'uuid' => $order->user->uuid,
            'first_name' => $order->user->first_name,
            'last_name' => $order->user->last_name,
            'full_name' => $order->user->full_name,
            'email' => $order->user->email,
        ] : null;

        return $data;
    }

    public function adminUpdateStatus(int $orderId, string $status, array $extra = []): array
    {
        $order = Order::with('items')->find($orderId);

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $allowedTransitions = [
            OrderStatus::PENDING => [OrderStatus::CONFIRMED, OrderStatus::CANCELLED],
            OrderStatus::CONFIRMED => [OrderStatus::PROCESSING, OrderStatus::CANCELLED],
            OrderStatus::PROCESSING => [OrderStatus::SHIPPING, OrderStatus::CANCELLED],
            OrderStatus::SHIPPING => [OrderStatus::DELIVERED],
            OrderStatus::DELIVERED => [OrderStatus::COMPLETED, OrderStatus::REFUNDED],
            OrderStatus::COMPLETED => [OrderStatus::REFUNDED],
        ];

        $allowed = $allowedTransitions[$order->status] ?? [];

        if (!in_array($status, $allowed)) {
            return [
                'success' => false,
                'message' => "Cannot change status from \"{$order->status}\" to \"{$status}\".",
            ];
        }

        return DB::transaction(function () use ($order, $status, $extra) {
            $updateData = ['status' => $status];

            // Set timestamps based on new status
            switch ($status) {
                case OrderStatus::CONFIRMED:
                    $updateData['confirmed_at'] = now();
                    break;
                case OrderStatus::SHIPPING:
                    $updateData['shipped_at'] = now();
                    if (!empty($extra['tracking_number'])) {
                        $updateData['tracking_number'] = $extra['tracking_number'];
                    }
                    if (!empty($extra['shipping_carrier'])) {
                        $updateData['shipping_carrier'] = $extra['shipping_carrier'];
                    }
                    break;
                case OrderStatus::DELIVERED:
                    $updateData['delivered_at'] = now();
                    break;
                case OrderStatus::CANCELLED:
                    $updateData['cancelled_at'] = now();
                    if (!empty($extra['cancel_reason'])) {
                        $updateData['cancel_reason'] = $extra['cancel_reason'];
                    }
                    break;
                case OrderStatus::REFUNDED:
                    $updateData['payment_status'] = PaymentStatus::REFUNDED;
                    break;
            }

            // Handle admin note
            if (isset($extra['admin_note'])) {
                $updateData['admin_note'] = $extra['admin_note'];
            }

            // Restore stock on cancel
            if ($status === OrderStatus::CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)
                            ->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $order->update($updateData);

            // Add points when order is completed
            if ($status === OrderStatus::COMPLETED) {
                $this->addPointsForCompletedOrder($order);
            }

            return [
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order' => $this->formatOrder($order->fresh('items')),
            ];
        });
    }

    private function addPointsForCompletedOrder(Order $order): void
    {
        $totalAmount = (int) $order->total_amount;
        $currency = $order->currency;

        // Convert to JPY if needed
        if ($currency !== 'JPY') {
            return;
        }

        if ($totalAmount >= 100000) {
            $points = 2000;
        } elseif ($totalAmount >= 50000) {
            $points = 1000;
        } else {
            $points = 500;
        }

        User::where('id', $order->user_id)->increment('point', $points);
    }

    private function buildShippingInfo(UserAddress $address, int $userId): array
    {
        $user = $address->user;
        $detail = $address->detail;

        $fullAddress = '';
        $city = '';

        if ($address->country_code === 'JP' && $detail) {
            $parts = array_filter([
                $detail->prefecture,
                $detail->city,
                $detail->ward_town,
                $detail->banchi,
                $detail->building_name,
                $detail->room_no,
            ]);
            $fullAddress = implode(' ', $parts);
            $city = $detail->city ?? $detail->prefecture ?? '';
        } elseif ($address->country_code === 'VN' && $detail) {
            $parts = array_filter([
                $detail->detail_address,
                $detail->ward_commune,
                $detail->district,
                $detail->province_city,
                $detail->building_name,
                $detail->room_no ? 'Phòng ' . $detail->room_no : null,
            ]);
            $fullAddress = implode(', ', $parts);
            $city = $detail->province_city ?? $detail->district ?? '';
        }

        return [
            'name' => $user->full_name,
            'phone' => $address->phone ?? '',
            'address' => $fullAddress,
            'city' => $city,
            'country' => $address->country_code,
            'postal_code' => $address->postal_code,
        ];
    }

    private function calculateShippingFee(string $shippingMethod, string $currency): int
    {
        $fees = [
            ShippingMethod::STANDARD => ['JPY' => 500, 'VND' => 30000],
            ShippingMethod::EXPRESS => ['JPY' => 1200, 'VND' => 60000],
            ShippingMethod::PICKUP => ['JPY' => 0, 'VND' => 0],
        ];

        return $fees[$shippingMethod][$currency] ?? 0;
    }

    private function calculateCodFee(string $paymentMethod, string $currency): int
    {
        if ($paymentMethod !== PaymentMethod::COD) {
            return 0;
        }

        // 代引き手数料: 1,200 JPY fixed
        return $currency === 'JPY' ? 1200 : 0;
    }

    private function calculateDepositAmount(string $paymentMethod, string $currency): int
    {
        if ($paymentMethod !== PaymentMethod::DEPOSIT_TRANSFER) {
            return 0;
        }

        // Deposit: 1,000,000 VND for Vietnamese customers
        return 1000000;
    }

    private function createStripePaymentIntent(Order $order, int $totalAmount, string $currency): array
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret_key'));

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $currency === 'JPY' ? $totalAmount : $totalAmount,
                'currency' => strtolower($currency),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Stripe payment failed: ' . $e->getMessage(),
            ];
        }
    }

    public function handleStripeWebhook(string $payload, string $signature): array
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Webhook signature verification failed.'];
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

            if ($order) {
                $order->update([
                    'payment_status' => PaymentStatus::PAID,
                    'paid_at' => now(),
                ]);
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $paymentIntent = $event->data->object;
            $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

            if ($order) {
                $order->update([
                    'payment_status' => PaymentStatus::FAILED,
                ]);
            }
        }

        return ['success' => true, 'message' => 'Webhook handled.'];
    }

    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'payment_receipt' => $order->payment_receipt ? CommonComponent::getFullUrl($order->payment_receipt) : null,
            'shipping_method' => $order->shipping_method,
            'subtotal' => $order->subtotal,
            'shipping_fee' => $order->shipping_fee,
            'cod_fee' => $order->cod_fee,
            'deposit_amount' => $order->deposit_amount,
            'discount_amount' => $order->discount_amount,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'shipping_name' => $order->shipping_name,
            'shipping_phone' => $order->shipping_phone,
            'shipping_address' => $order->shipping_address,
            'shipping_city' => $order->shipping_city,
            'shipping_country' => $order->shipping_country,
            'shipping_postal_code' => $order->shipping_postal_code,
            'note' => $order->note,
            'tracking_number' => $order->tracking_number,
            'shipping_carrier' => $order->shipping_carrier,
            'cancel_reason' => $order->cancel_reason,
            'confirmed_at' => $order->confirmed_at?->toIso8601String(),
            'paid_at' => $order->paid_at?->toIso8601String(),
            'shipped_at' => $order->shipped_at?->toIso8601String(),
            'delivered_at' => $order->delivered_at?->toIso8601String(),
            'cancelled_at' => $order->cancelled_at?->toIso8601String(),
            'created_at' => $order->created_at?->toIso8601String(),
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'product_sku' => $item->product_sku,
                'product_image' => $item->product_image ? CommonComponent::getFullUrl($item->product_image) : null,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ])->toArray(),
        ];
    }

    private function formatOrderSummary(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'shipping_method' => $order->shipping_method,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'total_items' => $order->items->sum('quantity'),
            'created_at' => $order->created_at?->toIso8601String(),
        ];
    }

    private function formatAdminOrderSummary(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'payment_receipt' => $order->payment_receipt ? CommonComponent::getFullUrl($order->payment_receipt) : null,
            'shipping_method' => $order->shipping_method,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'total_items' => $order->items->sum('quantity'),
            'user' => $order->user ? [
                'id' => $order->user->id,
                'first_name' => $order->user->first_name,
                'last_name' => $order->user->last_name,
                'full_name' => $order->user->full_name,
                'email' => $order->user->email,
            ] : null,
            'created_at' => $order->created_at?->toIso8601String(),
        ];
    }
}
