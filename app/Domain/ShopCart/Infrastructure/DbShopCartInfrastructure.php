<?php

namespace App\Domain\ShopCart\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopCart\Repository\ShopCartRepository;
use App\Models\Shop\Cart;
use App\Models\Shop\CartItem;
use App\Models\Shop\Product;
use App\Models\Shop\ProductColor;

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

        $cart->load(['items.product.brand:id,name,slug', 'items.product.images', 'items.productColor.images']);

        $items = $cart->items->map(function (CartItem $item) {
            $product = $item->product;
            $color   = $item->productColor;

            return [
                'id'                => $item->id,
                'product_id'        => $item->product_id,
                'product_color_id'  => $item->product_color_id,
                'quantity'          => $item->quantity,
                'price_at_addition' => $item->price_at_addition,
                'currency'          => $item->currency,
                'subtotal'          => $item->subtotal,
                'product'           => $product ? [
                    'id'               => $product->id,
                    'name'             => $product->name,
                    'slug'             => $product->slug,
                    'sku'              => $product->sku,
                    'price_jpy'        => $product->price_jpy,
                    'price_vnd'        => $product->price_vnd,
                    'stock_quantity'   => $product->stock_quantity,
                    'primary_image_url' => $product->primary_image_url,
                    'brand'            => $product->brand ? [
                        'id'   => $product->brand->id,
                        'name' => $product->brand->name,
                    ] : null,
                ] : null,
                'product_color' => $color ? [
                    'id'               => $color->id,
                    'color_name'       => $color->color_name,
                    'color_code'       => $color->color_code,
                    'sku'              => $color->sku,
                    'price_jpy'        => $color->price_jpy,
                    'price_vnd'        => $color->price_vnd,
                    'stock_quantity'   => $color->stock_quantity,
                    'primary_image_url' => $color->primaryImage?->image_url
                        ? CommonComponent::getFullUrl($color->primaryImage->image_url)
                        : null,
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

    public function addItem(?int $userId, ?string $deviceId, int $productId, int $quantity, string $currency, ?int $productColorId = null): array
    {
        $product = Product::where('id', $productId)->where('is_active', true)->first();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found or inactive.'];
        }

        // Resolve color variant if provided
        $color = null;
        if ($productColorId) {
            $color = ProductColor::where('id', $productColorId)
                ->where('product_id', $productId)
                ->where('is_active', true)
                ->first();

            if (!$color) {
                return ['success' => false, 'message' => 'Product color not found or inactive.'];
            }
        }

        $cart  = $this->findOrCreateCart($userId, $deviceId);
        $price = $currency === 'JPY'
            ? ($color ? $color->price_jpy : $product->price_jpy)
            : ($color ? $color->price_vnd : $product->price_vnd);

        // Find existing cart item matching both product and color
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('product_color_id', $productColorId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            $cartItem->update([
                'quantity'          => $newQuantity,
                'price_at_addition' => $price,
                'currency'          => $currency,
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id'          => $cart->id,
                'product_id'       => $productId,
                'product_color_id' => $productColorId,
                'quantity'         => $quantity,
                'price_at_addition' => $price,
                'currency'         => $currency,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Product added to cart.',
            'cart_item' => [
                'id'                => $cartItem->id,
                'product_id'        => $cartItem->product_id,
                'product_color_id'  => $cartItem->product_color_id,
                'quantity'          => $cartItem->quantity,
                'price_at_addition' => $cartItem->price_at_addition,
                'currency'          => $cartItem->currency,
                'subtotal'          => $cartItem->subtotal,
            ],
        ];
    }

    public function updateItemQuantity(?int $userId, ?string $deviceId, int $cartItemId, int $quantity): array
    {
        $cart = $this->findCart($userId, $deviceId);

        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found.'];
        }

        $cartItem = CartItem::where('id', $cartItemId)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $product = Product::where('id', $cartItem->product_id)->where('is_active', true)->first();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found or inactive.'];
        }

        $cartItem->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => 'Cart item quantity updated.',
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

    public function removeItem(?int $userId, ?string $deviceId, int $cartItemId): array
    {
        $cart = $this->findCart($userId, $deviceId);

        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found.'];
        }

        $cartItem = CartItem::where('id', $cartItemId)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $cartItem->delete();

        return [
            'success' => true,
            'message' => 'Cart item removed.',
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
                ->where('product_color_id', $guestItem->product_color_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity'          => $existingItem->quantity + $guestItem->quantity,
                    'price_at_addition' => $guestItem->price_at_addition,
                    'currency'          => $guestItem->currency,
                ]);
            } else {
                CartItem::create([
                    'cart_id'          => $userCart->id,
                    'product_id'       => $guestItem->product_id,
                    'product_color_id' => $guestItem->product_color_id,
                    'quantity'         => $guestItem->quantity,
                    'price_at_addition' => $guestItem->price_at_addition,
                    'currency'         => $guestItem->currency,
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
