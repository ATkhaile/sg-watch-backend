<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\StockType;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\Shop\ProductColor;
use Illuminate\Support\Facades\DB;

class FulfillWaitingOrdersService
{
    /**
     * Sau khi admin tăng stock, kiểm tra và tự động xử lý các đơn WAITING_ORDER
     * có sản phẩm liên quan. Đơn nào đủ hàng sẽ chuyển về PENDING và trừ stock.
     *
     * @param int      $productId      ID sản phẩm vừa được cập nhật stock
     * @param int|null $productColorId ID màu sắc (null nếu là sản phẩm không có màu)
     */
    public static function fulfill(int $productId, ?int $productColorId = null): void
    {
        // Tìm các đơn WAITING_ORDER có chứa sản phẩm/màu này
        $orderIds = OrderItem::where('product_id', $productId)
            ->when(
                $productColorId !== null,
                fn ($q) => $q->where('product_color_id', $productColorId),
                fn ($q) => $q->whereNull('product_color_id')
            )
            ->whereHas('order', fn ($q) => $q->where('status', OrderStatus::WAITING_ORDER))
            ->pluck('order_id')
            ->unique();

        if ($orderIds->isEmpty()) {
            return;
        }

        // Xử lý theo thứ tự tạo đơn (FIFO): đơn cũ nhất được ưu tiên
        $orders = Order::whereIn('id', $orderIds)
            ->where('status', OrderStatus::WAITING_ORDER)
            ->orderBy('created_at')
            ->with('items')
            ->get();

        foreach ($orders as $order) {
            DB::transaction(function () use ($order) {
                // Lock row để tránh race condition khi nhiều admin cập nhật đồng thời
                $lockedOrder = Order::where('id', $order->id)
                    ->where('status', OrderStatus::WAITING_ORDER)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedOrder) {
                    return; // Đơn đã được xử lý bởi transaction khác
                }

                // Kiểm tra tất cả items có đủ stock không (dùng giá trị mới nhất từ DB)
                foreach ($order->items as $item) {
                    if ($item->product_color_id) {
                        $stock = ProductColor::where('id', $item->product_color_id)->value('stock_quantity') ?? 0;
                    } else {
                        $stock = Product::where('id', $item->product_id)->value('stock_quantity') ?? 0;
                    }

                    if ($stock < $item->quantity) {
                        return; // Còn thiếu hàng, bỏ qua đơn này
                    }
                }

                // Đủ hàng → chuyển trạng thái và trừ stock
                $lockedOrder->update(['status' => OrderStatus::PENDING]);

                foreach ($order->items as $item) {
                    if ($item->product_color_id) {
                        ProductColor::where('id', $item->product_color_id)
                            ->decrement('stock_quantity', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)
                            ->decrement('stock_quantity', $item->quantity);

                        $updatedProduct = Product::find($item->product_id);
                        if ($updatedProduct && $updatedProduct->stock_quantity <= 0) {
                            $updatedProduct->update(['stock_type' => StockType::PRE_ORDER]);
                        }
                    }
                }
            });
        }
    }
}
