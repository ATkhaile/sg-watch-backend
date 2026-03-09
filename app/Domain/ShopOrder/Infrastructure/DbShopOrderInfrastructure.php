<?php

namespace App\Domain\ShopOrder\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopOrder\Repository\ShopOrderRepository;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ShippingMethod;
use App\Models\DiscountCode;
use App\Models\Shop\Cart;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserNotice;
use App\Models\FcmToken;
use App\Models\PusherInfo;
use App\Models\PointHistory;
use App\Enums\PushType;
use App\Enums\PointMasterType;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $address->load(['jpDetail.prefectureMaster', 'vnDetail']);

        $currency = $data['currency'] ?? 'JPY';

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

        $shippingFee = $this->calculateShippingFee($data['shipping_method'], $currency, $address);
        $codFee = $this->calculateCodFee($data['payment_method'], $currency);
        $depositAmount = $this->calculateDepositAmount($data['payment_method'], $currency);

        // Validate discount code
        $discountAmount = 0;
        $discountCode = null;
        if (!empty($data['discount_code'])) {
            $discountCode = DiscountCode::where('code', $data['discount_code'])
                ->where('is_active', true)
                ->where('quantity', '>', 0)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->first();

            if (!$discountCode) {
                return ['success' => false, 'message' => 'Discount code is invalid or not available.'];
            }

            $discountAmount = (int) floor($subtotal * $discountCode->percentage / 100);
        }

        // Validate and apply points
        $pointsUsed = 0;
        if (!empty($data['use_points']) && $currency === 'JPY') {
            $availablePoints = PointHistory::getAvailablePoints($userId);
            $requestedPoints = (int) $data['use_points'];

            if ($requestedPoints > $availablePoints) {
                return ['success' => false, 'message' => 'Insufficient points. Available: ' . $availablePoints];
            }

            $maxDeductible = $subtotal + $shippingFee + $codFee - $discountAmount;
            $pointsUsed = min($requestedPoints, $maxDeductible);
        }

        $totalAmount = $subtotal + $shippingFee + $codFee - $discountAmount - $pointsUsed;

        $shippingInfo = $this->buildShippingInfo($address, $userId);

        // Upload payment receipt
        $receiptPath = null;
        if (isset($data['payment_receipt']) && $data['payment_receipt'] instanceof UploadedFile) {
            $receiptPath = $data['payment_receipt']->store('orders/receipts/' . $userId, 'public');
        }

        return DB::transaction(function () use ($userId, $data, $cart, $currency, $subtotal, $shippingFee, $codFee, $depositAmount, $discountAmount, $discountCode, $pointsUsed, $totalAmount, $shippingInfo, $receiptPath) {
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
                'discount_amount' => $discountAmount,
                'points_used' => $pointsUsed,
                'total_amount' => $totalAmount,
                'currency' => $currency,
                'coupon_code' => $discountCode?->code,
                'discount_code_id' => $discountCode?->id,
                'shipping_name' => $shippingInfo['name'],
                'shipping_phone' => $shippingInfo['phone'],
                'shipping_address' => $shippingInfo['address'],
                'shipping_city' => $shippingInfo['city'],
                'shipping_country' => $shippingInfo['country'],
                'shipping_postal_code' => $shippingInfo['postal_code'],
                'note' => $data['note'] ?? null,
            ]);

            // Decrement discount code quantity
            if ($discountCode) {
                $discountCode->decrement('quantity', 1);
            }

            // Deduct points from user (FIFO: dùng điểm cũ nhất trước)
            if ($pointsUsed > 0) {
                PointHistory::spendPoints($userId, $pointsUsed);
                PointHistory::syncUserPoint($userId);
            }

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

            $order->load('items.product.category', 'items.product.brand');

            // Notify all admins about new order
            $this->sendOrderNotificationToAdmins(
                "Đơn hàng mới #{$order->order_number}",
                "Có đơn hàng mới được tạo.",
                [
                    'type' => 'order_created',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => OrderStatus::PENDING,
                ]
            );

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
            ->with(['items.product.category', 'items.product.brand'])
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
            ->with(['items.product.category', 'items.product.brand'])
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

            // Restore discount code quantity
            if ($order->discount_code_id) {
                DiscountCode::where('id', $order->discount_code_id)->increment('quantity', 1);
            }

            // Restore points to user
            if ($order->points_used > 0) {
                PointHistory::create([
                    'user_id'             => $order->user_id,
                    'point'               => $order->points_used,
                    'remaining_point'     => $order->points_used,
                    'memo'                => 'Refund points from cancelled order #' . $order->order_number,
                    'point_type'          => PointMasterType::ORDER_BONUS,
                    'last_update_user_id' => $order->user_id,
                    'expired_at'          => Carbon::now()->addMonths(6),
                ]);
                PointHistory::syncUserPoint($order->user_id);
            }

            $order->update([
                'status' => OrderStatus::CANCELLED,
                'cancel_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'order' => $this->formatOrder($order->fresh(['items.product.category', 'items.product.brand'])),
            ];
        });
    }

    public function updatePaymentReceipt(int $userId, int $orderId, \Illuminate\Http\UploadedFile $paymentReceipt): array
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->with(['items.product.category', 'items.product.brand'])
            ->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $completedStatuses = [OrderStatus::COMPLETED, OrderStatus::CANCELLED, OrderStatus::REFUNDED];
        if (in_array($order->status, $completedStatuses)) {
            return ['success' => false, 'message' => 'Cannot update payment receipt for this order status.'];
        }

        // Delete old receipt
        if ($order->payment_receipt && Storage::disk('public')->exists($order->payment_receipt)) {
            Storage::disk('public')->delete($order->payment_receipt);
        }

        $path = $paymentReceipt->store('orders/receipts/' . $userId, 'public');
        $order->update(['payment_receipt' => $path]);

        return [
            'success' => true,
            'message' => 'Payment receipt updated successfully.',
            'order' => $this->formatOrder($order->fresh(['items.product.category', 'items.product.brand'])),
        ];
    }

    public function adminGetList(array $filters): array
    {
        $query = Order::query()
            ->with(['items.product.category', 'items.product.brand', 'user:id,uuid,first_name,last_name,email']);

        // Filter by order_type
        if (!empty($filters['order_type'])) {
            $query->where('order_type', $filters['order_type']);
        }

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

        // Filter by brand_id (through order items -> product)
        if (!empty($filters['brand_id'])) {
            $query->whereHas('items', function ($iq) use ($filters) {
                $iq->whereHas('product', function ($pq) use ($filters) {
                    $pq->where('brand_id', $filters['brand_id']);
                });
            });
        }

        // Filter by category_id (through order items -> product)
        if (!empty($filters['category_id'])) {
            $query->whereHas('items', function ($iq) use ($filters) {
                $iq->whereHas('product', function ($pq) use ($filters) {
                    $pq->where('category_id', $filters['category_id']);
                });
            });
        }

        // General keyword search (order_number, user name/email, product sku/name)
        if (!empty($filters['keyword'])) {
            $keyword = '%' . $filters['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('order_number', 'like', $keyword)
                  ->orWhere('customer_name', 'like', $keyword)
                  ->orWhereHas('user', function ($uq) use ($keyword) {
                      $uq->where('first_name', 'like', $keyword)
                        ->orWhere('last_name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword);
                  })
                  ->orWhereHas('items', function ($iq) use ($keyword) {
                      $iq->where('product_sku', 'like', $keyword)
                        ->orWhere('product_name', 'like', $keyword);
                  });
            });
        }

        // Search by order number
        if (!empty($filters['order_number'])) {
            $query->where('order_number', 'like', '%' . $filters['order_number'] . '%');
        }

        // Search by user name/email
        if (!empty($filters['user_keyword'])) {
            $userKeyword = '%' . $filters['user_keyword'] . '%';
            $query->whereHas('user', function ($uq) use ($userKeyword) {
                $uq->where('first_name', 'like', $userKeyword)
                  ->orWhere('last_name', 'like', $userKeyword)
                  ->orWhere('email', 'like', $userKeyword);
            });
        }

        // Search by product sku/name
        if (!empty($filters['product_keyword'])) {
            $productKeyword = '%' . $filters['product_keyword'] . '%';
            $query->whereHas('items', function ($iq) use ($productKeyword) {
                $iq->where('product_sku', 'like', $productKeyword)
                  ->orWhere('product_name', 'like', $productKeyword);
            });
        }

        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
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
            'orders' => collect($paginator->items())->map(fn (Order $order) => $this->formatAdminOrderDetail($order))->toArray(),
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

        return $this->formatAdminOrderDetail($order);
    }

    public function adminUpdateStatus(int $orderId, string $status, array $extra = []): array
    {
        $order = Order::with(['items.product.category', 'items.product.brand'])->find($orderId);

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        if ($order->status === $status) {
            return ['success' => false, 'message' => 'Order is already in this status.'];
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

            // Deduct awarded points when moving away from completed (chỉ áp dụng cho đơn có user)
            if ($order->user_id && $order->status === OrderStatus::COMPLETED && $status !== OrderStatus::COMPLETED) {
                if ($order->points_earned > 0) {
                    // Tìm và xóa remaining của PointHistory order bonus tương ứng
                    $orderBonusHistory = PointHistory::where('user_id', $order->user_id)
                        ->where('point_type', PointMasterType::ORDER_BONUS)
                        ->where('memo', 'like', '%#' . $order->order_number)
                        ->where('remaining_point', '>', 0)
                        ->first();

                    if ($orderBonusHistory) {
                        $orderBonusHistory->update(['remaining_point' => 0]);
                    }

                    PointHistory::syncUserPoint($order->user_id);
                    $updateData['points_earned'] = 0;
                }
            }

            // Restore stock and discount code on cancel
            if ($status === OrderStatus::CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)
                            ->increment('stock_quantity', $item->quantity);
                    }
                }

                // Restore discount code quantity
                if ($order->discount_code_id) {
                    DiscountCode::where('id', $order->discount_code_id)->increment('quantity', 1);
                }

                // Restore points to user (chỉ áp dụng cho đơn có user)
                if ($order->user_id && $order->points_used > 0) {
                    PointHistory::create([
                        'user_id'             => $order->user_id,
                        'point'               => $order->points_used,
                        'remaining_point'     => $order->points_used,
                        'memo'                => 'Refund points from cancelled order #' . $order->order_number,
                        'point_type'          => PointMasterType::ORDER_BONUS,
                        'last_update_user_id' => $order->user_id,
                        'expired_at'          => Carbon::now()->addMonths(6),
                    ]);
                    PointHistory::syncUserPoint($order->user_id);
                }
            }

            $order->update($updateData);

            // Add points when order is completed
            if ($status === OrderStatus::COMPLETED) {
                $this->addPointsForCompletedOrder($order);
            }

            // Create user notice for order status change
            $statusLabel = $this->getVietnameseStatusLabel($status);
            $noticeTitle = "Đơn hàng #{$order->order_number} đã được cập nhật";
            $noticeContent = "Đơn hàng của bạn đã chuyển sang trạng thái: {$statusLabel}";
            $noticeData = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $status,
            ];

            // Chỉ gửi thông báo cho user nếu đơn hàng có user_id (không phải walk-in)
            if ($order->user_id) {
                UserNotice::create([
                    'user_id' => $order->user_id,
                    'type' => 'order_status',
                    'title' => $noticeTitle,
                    'content' => $noticeContent,
                    'data' => $noticeData,
                ]);

                // Send Firebase push notification to customer
                $this->sendOrderStatusPush($order->user_id, $noticeTitle, $noticeContent, $noticeData);
            }

            // Notify all admins about order status change
            $this->sendOrderNotificationToAdmins(
                "Đơn hàng #{$order->order_number} cập nhật trạng thái",
                "Trạng thái đơn hàng đã chuyển sang: {$statusLabel}",
                [
                    'type' => 'order_status',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => $order->getOriginal('status'),
                    'new_status' => $status,
                ]
            );

            return [
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order' => $this->formatOrder($order->fresh(['items.product.category', 'items.product.brand'])),
            ];
        });
    }

    public function adminCreateOrder(array $data): array
    {
        $orderType = $data['order_type'] ?? OrderType::ONLINE;
        $userId = $data['user_id'] ?? null;
        $user = null;

        if ($orderType === OrderType::ONLINE) {
            $user = User::find($userId);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found.'];
            }
        } elseif ($userId) {
            $user = User::find($userId);
        }

        $currency = $data['currency'];
        $items = $data['items'];

        // Validate products and calculate subtotal
        $orderItems = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (!$product || !$product->is_active) {
                return ['success' => false, 'message' => "Product ID {$item['product_id']} is not available."];
            }

            if ($product->stock_quantity < $item['quantity']) {
                return ['success' => false, 'message' => "Insufficient stock for \"{$product->name}\". Available: {$product->stock_quantity}"];
            }

            $unitPrice = isset($item['unit_price'])
                ? (int) $item['unit_price']
                : ($currency === 'JPY' ? (int) $product->price_jpy : (int) $product->price_vnd);

            $totalPrice = $unitPrice * $item['quantity'];
            $subtotal += $totalPrice;

            $orderItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ];
        }

        $shippingFee = (int) ($data['shipping_fee'] ?? 0);
        $codFee = (int) ($data['cod_fee'] ?? 0);
        $depositAmount = (int) ($data['deposit_amount'] ?? 0);
        $discountAmount = (int) ($data['discount_amount'] ?? 0);
        $totalAmount = $subtotal + $shippingFee + $codFee - $discountAmount;

        $status = $data['status'] ?? OrderStatus::PENDING;
        $paymentStatus = $data['payment_status'] ?? PaymentStatus::PENDING;

        $orderType = $data['order_type'] ?? OrderType::ONLINE;

        return DB::transaction(function () use ($userId, $data, $orderItems, $currency, $subtotal, $shippingFee, $codFee, $depositAmount, $discountAmount, $totalAmount, $status, $paymentStatus, $orderType) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'order_type' => $orderType,
                'customer_name' => $data['customer_name'] ?? null,
                'user_id' => $userId,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $data['payment_method'],
                'shipping_method' => $data['shipping_method'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'cod_fee' => $codFee,
                'deposit_amount' => $depositAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => $currency,
                'shipping_name' => $data['shipping_name'] ?? null,
                'shipping_phone' => $data['shipping_phone'] ?? null,
                'shipping_email' => $data['shipping_email'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'shipping_city' => $data['shipping_city'] ?? null,
                'shipping_country' => $data['shipping_country'] ?? null,
                'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
                'note' => $data['note'] ?? null,
                'admin_note' => $data['admin_note'] ?? null,
                'confirmed_at' => $status === OrderStatus::CONFIRMED ? now() : null,
                'paid_at' => $paymentStatus === PaymentStatus::PAID ? now() : null,
            ]);

            foreach ($orderItems as $item) {
                $product = $item['product'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $product->primary_image_url,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                Product::where('id', $product->id)
                    ->decrement('stock_quantity', $item['quantity']);
            }

            $order->load('items.product.category', 'items.product.brand', 'user:id,uuid,first_name,last_name,email');

            // Notify all admins about new order created by admin
            $this->sendOrderNotificationToAdmins(
                "Đơn hàng mới #{$order->order_number}",
                "Có đơn hàng mới được tạo bởi admin.",
                [
                    'type' => 'order_created',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $status,
                ]
            );

            return [
                'success' => true,
                'message' => 'Order created successfully.',
                'order' => $this->formatAdminOrderDetail($order),
            ];
        });
    }

    public function adminUpdateOrder(int $orderId, array $data): array
    {
        $order = Order::with(['items.product'])->find($orderId);

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $completedStatuses = [OrderStatus::COMPLETED, OrderStatus::CANCELLED, OrderStatus::REFUNDED];
        if (in_array($order->status, $completedStatuses)) {
            return ['success' => false, 'message' => 'Cannot update order in current status.'];
        }

        $currency = $data['currency'] ?? $order->currency;

        try {
            return DB::transaction(function () use ($order, $data, $currency) {
                $subtotal = (int) $order->subtotal;

                // Update items if provided
                if (isset($data['items'])) {
                    $newItems = $data['items'];

                    // Validate new products
                    $orderItems = [];
                    foreach ($newItems as $item) {
                        $product = Product::find($item['product_id']);

                        if (!$product || !$product->is_active) {
                            throw new \RuntimeException("Product ID {$item['product_id']} is not available.");
                        }

                        $orderItems[] = [
                            'product' => $product,
                            'quantity' => $item['quantity'],
                            'unit_price' => isset($item['unit_price'])
                                ? (int) $item['unit_price']
                                : ($currency === 'JPY' ? (int) $product->price_jpy : (int) $product->price_vnd),
                        ];
                    }

                    // Restore old stock
                    foreach ($order->items as $oldItem) {
                        if ($oldItem->product_id) {
                            Product::where('id', $oldItem->product_id)
                                ->increment('stock_quantity', $oldItem->quantity);
                        }
                    }

                    // Check stock for new items
                    foreach ($orderItems as $item) {
                        $product = $item['product']->fresh();
                        if ($product->stock_quantity < $item['quantity']) {
                            throw new \RuntimeException("Insufficient stock for \"{$product->name}\". Available: {$product->stock_quantity}");
                        }
                    }

                    // Delete old items and create new ones
                    $order->items()->delete();

                    $subtotal = 0;
                    foreach ($orderItems as $item) {
                        $product = $item['product'];
                        $totalPrice = $item['unit_price'] * $item['quantity'];
                        $subtotal += $totalPrice;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product_sku' => $product->sku,
                            'product_image' => $product->primary_image_url,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'total_price' => $totalPrice,
                        ]);

                        Product::where('id', $product->id)
                            ->decrement('stock_quantity', $item['quantity']);
                    }
                }

                $updateData = [];

                if (array_key_exists('customer_name', $data)) {
                    $updateData['customer_name'] = $data['customer_name'];
                }
                if (isset($data['currency'])) {
                    $updateData['currency'] = $data['currency'];
                }
                if (isset($data['payment_method'])) {
                    $updateData['payment_method'] = $data['payment_method'];
                }
                if (isset($data['shipping_method'])) {
                    $updateData['shipping_method'] = $data['shipping_method'];
                }
                if (isset($data['shipping_name'])) {
                    $updateData['shipping_name'] = $data['shipping_name'];
                }
                if (isset($data['shipping_phone'])) {
                    $updateData['shipping_phone'] = $data['shipping_phone'];
                }
                if (isset($data['shipping_email'])) {
                    $updateData['shipping_email'] = $data['shipping_email'];
                }
                if (isset($data['shipping_address'])) {
                    $updateData['shipping_address'] = $data['shipping_address'];
                }
                if (isset($data['shipping_city'])) {
                    $updateData['shipping_city'] = $data['shipping_city'];
                }
                if (isset($data['shipping_country'])) {
                    $updateData['shipping_country'] = $data['shipping_country'];
                }
                if (isset($data['shipping_postal_code'])) {
                    $updateData['shipping_postal_code'] = $data['shipping_postal_code'];
                }
                if (array_key_exists('note', $data)) {
                    $updateData['note'] = $data['note'];
                }
                if (array_key_exists('admin_note', $data)) {
                    $updateData['admin_note'] = $data['admin_note'];
                }

                // Recalculate totals
                $shippingFee = isset($data['shipping_fee']) ? (int) $data['shipping_fee'] : (int) $order->shipping_fee;
                $codFee = isset($data['cod_fee']) ? (int) $data['cod_fee'] : (int) $order->cod_fee;
                $depositAmount = isset($data['deposit_amount']) ? (int) $data['deposit_amount'] : (int) $order->deposit_amount;
                $discountAmount = isset($data['discount_amount']) ? (int) $data['discount_amount'] : (int) $order->discount_amount;

                $updateData['subtotal'] = $subtotal;
                $updateData['shipping_fee'] = $shippingFee;
                $updateData['cod_fee'] = $codFee;
                $updateData['deposit_amount'] = $depositAmount;
                $updateData['discount_amount'] = $discountAmount;
                $updateData['total_amount'] = $subtotal + $shippingFee + $codFee - $discountAmount;

                $order->update($updateData);

                $order->load('items.product.category', 'items.product.brand', 'user:id,uuid,first_name,last_name,email');

                return [
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'order' => $this->formatAdminOrderDetail($order),
                ];
            });
        } catch (\RuntimeException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function adminUpdatePaymentStatus(int $orderId, string $paymentStatus): array
    {
        $order = Order::with(['items.product.category', 'items.product.brand'])->find($orderId);

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        if ($order->payment_status === $paymentStatus) {
            return ['success' => false, 'message' => 'Order already has this payment status.'];
        }

        $updateData = ['payment_status' => $paymentStatus];

        if ($paymentStatus === PaymentStatus::PAID) {
            $updateData['paid_at'] = now();
        } elseif ($paymentStatus === PaymentStatus::PENDING) {
            $updateData['paid_at'] = null;
        }

        $order->update($updateData);

        // Create user notice for payment status change
        $paymentLabel = $this->getVietnamesePaymentStatusLabel($paymentStatus);
        $noticeTitle = "Đơn hàng #{$order->order_number} cập nhật thanh toán";
        $noticeContent = "Trạng thái thanh toán đã chuyển sang: {$paymentLabel}";
        $noticeData = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_status' => $paymentStatus,
        ];

        // Chỉ gửi thông báo cho user nếu đơn hàng có user_id (không phải walk-in)
        if ($order->user_id) {
            UserNotice::create([
                'user_id' => $order->user_id,
                'type' => 'payment_status',
                'title' => $noticeTitle,
                'content' => $noticeContent,
                'data' => $noticeData,
            ]);

            $this->sendOrderStatusPush($order->user_id, $noticeTitle, $noticeContent, $noticeData);
        }

        // Notify all admins about payment status change
        $this->sendOrderNotificationToAdmins(
            "Đơn hàng #{$order->order_number} cập nhật thanh toán",
            "Trạng thái thanh toán đã chuyển sang: {$paymentLabel}",
            [
                'type' => 'payment_status',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $paymentStatus,
            ]
        );

        return [
            'success' => true,
            'message' => 'Payment status updated successfully.',
            'order' => $this->formatOrder($order->fresh(['items.product.category', 'items.product.brand'])),
        ];
    }

    private function getVietnamesePaymentStatusLabel(string $paymentStatus): string
    {
        return match ($paymentStatus) {
            PaymentStatus::PENDING => 'Chờ thanh toán',
            PaymentStatus::PAID => 'Đã thanh toán',
            PaymentStatus::FAILED => 'Thanh toán thất bại',
            PaymentStatus::REFUNDED => 'Đã hoàn tiền',
            default => $paymentStatus,
        };
    }

    private function addPointsForCompletedOrder(Order $order): void
    {
        // Không cộng điểm cho đơn walk-in không có user
        if (!$order->user_id) {
            return;
        }

        $totalAmount = (int) $order->total_amount;
        $currency = $order->currency;

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

        PointHistory::create([
            'user_id'             => $order->user_id,
            'point'               => $points,
            'remaining_point'     => $points,
            'memo'                => 'Order bonus #' . $order->order_number,
            'point_type'          => PointMasterType::ORDER_BONUS,
            'last_update_user_id' => $order->user_id,
            'expired_at'          => Carbon::now()->addMonths(6),
        ]);

        PointHistory::syncUserPoint($order->user_id);
        $order->update(['points_earned' => $points]);
    }

    private function getVietnameseStatusLabel(string $status): string
    {
        return match ($status) {
            OrderStatus::PENDING => 'Chờ xử lý',
            OrderStatus::CONFIRMED => 'Đã xác nhận',
            OrderStatus::PROCESSING => 'Đang xử lý',
            OrderStatus::SHIPPING => 'Đang giao hàng',
            OrderStatus::DELIVERED => 'Đã giao hàng',
            OrderStatus::COMPLETED => 'Hoàn thành',
            OrderStatus::CANCELLED => 'Đã hủy',
            OrderStatus::REFUNDED => 'Đã hoàn tiền',
            default => $status,
        };
    }

    private function sendOrderStatusPush(int $userId, string $title, string $body, array $data): void
    {
        try {
            $user = User::find($userId);
            if ($user && !$user->push_notification_enabled) {
                return;
            }

            $fcmTokens = FcmToken::where('user_id', $userId)
                ->whereNull('deleted_at')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($fcmTokens)) {
                return;
            }

            $accessToken = $this->getFirebaseAccessToken();
            if (!$accessToken) {
                return;
            }

            $setting = PusherInfo::where('push_type', PushType::FIREBASE)->first();
            $projectId = $setting ? $setting->firebase_project_id : config('services.firebase.project_id');
            if (!$projectId) {
                return;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            $stringData = array_map('strval', $data);
            $stringData['type'] = 'order_status';

            foreach ($fcmTokens as $token) {
                try {
                    $this->sendFirebasePush($url, $accessToken, $token, $title, $body, $stringData);
                } catch (\Throwable $e) {
                    Log::channel('log_notification_push')->error('Firebase push failed for token', [
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::channel('log_notification_push')->error('Firebase push failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendOrderNotificationToAdmins(string $title, string $body, array $data): void
    {
        try {
            $adminUserIds = User::where('is_system_admin', true)
                ->pluck('id')
                ->toArray();

            if (empty($adminUserIds)) {
                return;
            }

            // Create UserNotice for each admin
            foreach ($adminUserIds as $adminId) {
                UserNotice::create([
                    'user_id' => $adminId,
                    'type' => $data['type'] ?? 'order_status',
                    'title' => $title,
                    'content' => $body,
                    'data' => $data,
                ]);
            }

            // Filter out admins with push notifications disabled
            $enabledAdminIds = User::whereIn('id', $adminUserIds)
                ->where('push_notification_enabled', true)
                ->pluck('id')
                ->toArray();

            // Send Firebase push to all admin FCM tokens
            $fcmTokens = FcmToken::whereIn('user_id', $enabledAdminIds)
                ->whereNull('deleted_at')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($fcmTokens)) {
                return;
            }

            $accessToken = $this->getFirebaseAccessToken();
            if (!$accessToken) {
                return;
            }

            $setting = PusherInfo::where('push_type', PushType::FIREBASE)->first();
            $projectId = $setting ? $setting->firebase_project_id : config('services.firebase.project_id');
            if (!$projectId) {
                return;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            $stringData = array_map('strval', $data);

            foreach ($fcmTokens as $token) {
                try {
                    $this->sendFirebasePush($url, $accessToken, $token, $title, $body, $stringData);
                } catch (\Throwable $e) {
                    Log::channel('log_notification_push')->error('Firebase push to admin failed for token', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::channel('log_notification_push')->error('Firebase push to admins failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendFirebasePush(string $url, string $accessToken, string $fcmToken, string $title, string $body, array $data): void
    {
        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
                'android' => [
                    'priority' => 'HIGH',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'order_updates',
                    ],
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1,
                            'alert' => [
                                'title' => $title,
                                'body' => $body,
                            ],
                            'mutable-content' => 1,
                        ],
                    ],
                ],
            ],
        ];

        $httpClient = new Client();
        $httpClient->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'timeout' => 30,
        ]);
    }

    private function getFirebaseAccessToken(): ?string
    {
        try {
            $setting = PusherInfo::where('push_type', PushType::FIREBASE)->first();
            $credentialsPath = $setting ? $setting->firebase_credentials_path : config('services.firebase.credentials_path');
            if (!$credentialsPath) {
                return null;
            }

            $fullPath = base_path($credentialsPath);
            if (!file_exists($fullPath)) {
                return null;
            }

            $json = json_decode(file_get_contents($fullPath), true);
            if (!$json || !isset($json['client_email']) || !isset($json['private_key'])) {
                return null;
            }

            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $now = time();
            $jwtPayload = [
                'iss' => $json['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ];

            $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
            $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($jwtPayload)), '+/', '-_'), '=');
            $data = $base64UrlHeader . '.' . $base64UrlPayload;

            if (!openssl_sign($data, $signature, $json['private_key'], 'sha256WithRSAEncryption')) {
                return null;
            }

            $jwt = $data . '.' . rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

            $httpClient = new Client();
            $response = $httpClient->post('https://oauth2.googleapis.com/token', [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ],
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['access_token'] ?? null;
            }

            return null;
        } catch (\Throwable $e) {
            Log::channel('log_notification_push')->error('Failed to get Firebase access token', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function buildShippingInfo(UserAddress $address, int $userId): array
    {
        $user = $address->user;
        $detail = $address->detail;

        $fullAddress = '';
        $city = '';

        if ($address->country_code === 'JP' && $detail) {
            $prefectureName = $detail->prefectureMaster?->name ?? '';
            $parts = array_filter([
                $prefectureName,
                $detail->ward_town,
                $detail->banchi,
                $detail->building_name,
                $detail->room_no,
            ]);
            $fullAddress = implode(' ', $parts);
            $city = $prefectureName;
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

    private function calculateShippingFee(string $shippingMethod, string $currency, UserAddress $address): int
    {
        if ($shippingMethod === ShippingMethod::PICKUP) {
            return 0;
        }

        // VN: 1,000 JPY = 175,000 VND
        if ($address->country_code === 'VN') {
            return $currency === 'JPY' ? 1000 : 175000;
        }

        // JP: Hokkaido / Okinawa = 1,500 JPY, others = 0 JPY
        if ($address->country_code === 'JP' && $address->jpDetail) {
            $prefectureName = $address->jpDetail->prefectureMaster?->name ?? '';
            if (in_array($prefectureName, ['北海道', '沖縄県'])) {
                return 1500;
            }
        }

        return 0;
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
        Log::info('Stripe Webhook received', [
            'signature' => $signature,
            'webhook_secret' => config('services.stripe.webhook_secret'),
            'payload_length' => strlen($payload),
        ]);

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Throwable $e) {
            Log::error('Stripe Webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
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

                // Create user notice and send Firebase push notification
                $paymentLabel = $this->getVietnamesePaymentStatusLabel(PaymentStatus::PAID);
                $noticeTitle = "Đơn hàng #{$order->order_number} cập nhật thanh toán";
                $noticeContent = "Trạng thái thanh toán đã chuyển sang: {$paymentLabel}";
                $noticeData = [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => PaymentStatus::PAID,
                ];

                UserNotice::create([
                    'user_id' => $order->user_id,
                    'type' => 'payment_status',
                    'title' => $noticeTitle,
                    'content' => $noticeContent,
                    'data' => $noticeData,
                ]);

                $this->sendOrderStatusPush($order->user_id, $noticeTitle, $noticeContent, $noticeData);

                // Notify all admins about payment success
                $this->sendOrderNotificationToAdmins(
                    "Đơn hàng #{$order->order_number} cập nhật thanh toán",
                    "Trạng thái thanh toán đã chuyển sang: {$paymentLabel}",
                    [
                        'type' => 'payment_status',
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'payment_status' => PaymentStatus::PAID,
                    ]
                );
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $paymentIntent = $event->data->object;
            $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

            if ($order) {
                $order->update([
                    'payment_status' => PaymentStatus::FAILED,
                ]);

                // Create user notice and send Firebase push notification
                $paymentLabel = $this->getVietnamesePaymentStatusLabel(PaymentStatus::FAILED);
                $noticeTitle = "Đơn hàng #{$order->order_number} cập nhật thanh toán";
                $noticeContent = "Trạng thái thanh toán đã chuyển sang: {$paymentLabel}";
                $noticeData = [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => PaymentStatus::FAILED,
                ];

                UserNotice::create([
                    'user_id' => $order->user_id,
                    'type' => 'payment_status',
                    'title' => $noticeTitle,
                    'content' => $noticeContent,
                    'data' => $noticeData,
                ]);

                $this->sendOrderStatusPush($order->user_id, $noticeTitle, $noticeContent, $noticeData);

                // Notify all admins about payment failure
                $this->sendOrderNotificationToAdmins(
                    "Đơn hàng #{$order->order_number} cập nhật thanh toán",
                    "Trạng thái thanh toán đã chuyển sang: {$paymentLabel}",
                    [
                        'type' => 'payment_status',
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'payment_status' => PaymentStatus::FAILED,
                    ]
                );
            }
        }

        return ['success' => true, 'message' => 'Webhook handled.'];
    }

    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'order_type' => $order->order_type,
            'customer_name' => $order->customer_name,
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
            'points_used' => $order->points_used,
            'points_earned' => $order->points_earned,
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
                'category' => $item->product?->category ? [
                    'id' => $item->product->category->id,
                    'name' => $item->product->category->name,
                    'slug' => $item->product->category->slug,
                ] : null,
                'brand' => $item->product?->brand ? [
                    'id' => $item->product->brand->id,
                    'name' => $item->product->brand->name,
                    'slug' => $item->product->brand->slug,
                ] : null,
            ])->toArray(),
        ];
    }

    private function formatOrderSummary(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'order_type' => $order->order_type,
            'customer_name' => $order->customer_name,
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

    private function formatAdminOrderDetail(Order $order): array
    {
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
}
