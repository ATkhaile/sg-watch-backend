<?php

namespace App\Domain\ShopOrder\Infrastructure;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingMethod;
use App\Models\Shop\Cart;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;

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
        $totalAmount = $subtotal + $shippingFee;

        $shippingInfo = $this->buildShippingInfo($address, $userId);

        return DB::transaction(function () use ($userId, $data, $cart, $currency, $subtotal, $shippingFee, $totalAmount, $shippingInfo) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'status' => OrderStatus::PENDING,
                'payment_status' => PaymentStatus::PENDING,
                'payment_method' => $data['payment_method'],
                'shipping_method' => $data['shipping_method'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
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

            $order->load('items');

            return [
                'success' => true,
                'message' => 'Order placed successfully.',
                'order' => $this->formatOrder($order),
            ];
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

    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'shipping_method' => $order->shipping_method,
            'subtotal' => $order->subtotal,
            'shipping_fee' => $order->shipping_fee,
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
                'product_image' => $item->product_image,
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
}
