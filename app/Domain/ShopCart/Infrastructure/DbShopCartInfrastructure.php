<?php

namespace App\Domain\ShopCart\Infrastructure;

use App\Domain\ShopCart\Repository\ShopCartRepository;
use App\Models\Shop\Cart;
use App\Models\Shop\CartItem;
use App\Models\Shop\Product;

class DbShopCartInfrastructure implements ShopCartRepository
{
    public function getCart(?int $userId, ?string $deviceId): array
    {
        $cart = $this->findCart($userId, $deviceId);

        if (!$cart) {
            return [
                'items' => [],
                'total_items' => 0,
                'total_jpy' => 0,
                'total_vnd' => 0,
            ];
        }

        $cart->load(['items.product.brand:id,name,slug', 'items.product.images']);

        $items = $cart->items->map(function (CartItem $item) {
            $product = $item->product;

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price_at_addition' => $item->price_at_addition,
                'currency' => $item->currency,
                'subtotal' => $item->subtotal,
                'product' => $product ? [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'price_jpy' => $product->price_jpy,
                    'price_vnd' => $product->price_vnd,
                    'stock_quantity' => $product->stock_quantity,
                    'primary_image_url' => $product->primary_image_url,
                    'brand' => $product->brand ? [
                        'id' => $product->brand->id,
                        'name' => $product->brand->name,
                    ] : null,
                ] : null,
            ];
        })->toArray();

        return [
            'items' => $items,
            'total_items' => $cart->total_items,
            'total_jpy' => $cart->items->sum(fn ($item) => $item->currency === 'JPY' ? $item->subtotal : 0),
            'total_vnd' => $cart->items->sum(fn ($item) => $item->currency === 'VND' ? $item->subtotal : 0),
        ];
    }

    public function addItem(?int $userId, ?string $deviceId, int $productId, int $quantity, string $currency): array
    {
        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found or inactive.'];
        }

        if ($product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock. Available: ' . $product->stock_quantity];
        }

        $cart = $this->findOrCreateCart($userId, $deviceId);

        $price = $currency === 'JPY' ? $product->price_jpy : $product->price_vnd;

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            if ($product->stock_quantity < $newQuantity) {
                return ['success' => false, 'message' => 'Insufficient stock. Available: ' . $product->stock_quantity . ', in cart: ' . $cartItem->quantity];
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'price_at_addition' => $price,
                'currency' => $currency,
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price_at_addition' => $price,
                'currency' => $currency,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Product added to cart.',
            'cart_item' => [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price_at_addition' => $cartItem->price_at_addition,
                'currency' => $cartItem->currency,
                'subtotal' => $cartItem->subtotal,
            ],
        ];
    }

    public function mergeCarts(string $deviceId, int $userId): void
    {
        $guestCart = Cart::where('device_id', $deviceId)->whereNull('user_id')->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );

        foreach ($guestCart->items as $guestItem) {
            $existingItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity,
                    'price_at_addition' => $guestItem->price_at_addition,
                    'currency' => $guestItem->currency,
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                    'price_at_addition' => $guestItem->price_at_addition,
                    'currency' => $guestItem->currency,
                ]);
            }
        }

        $guestCart->items()->delete();
        $guestCart->delete();
    }

    private function findCart(?int $userId, ?string $deviceId): ?Cart
    {
        if ($userId) {
            return Cart::where('user_id', $userId)->first();
        }

        if ($deviceId) {
            return Cart::where('device_id', $deviceId)->whereNull('user_id')->first();
        }

        return null;
    }

    private function findOrCreateCart(?int $userId, ?string $deviceId): Cart
    {
        if ($userId) {
            return Cart::firstOrCreate(
                ['user_id' => $userId],
                ['user_id' => $userId]
            );
        }

        return Cart::firstOrCreate(
            ['device_id' => $deviceId, 'user_id' => null],
            ['device_id' => $deviceId]
        );
    }
}
