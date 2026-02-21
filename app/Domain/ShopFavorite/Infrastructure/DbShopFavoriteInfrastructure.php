<?php

namespace App\Domain\ShopFavorite\Infrastructure;

use App\Domain\ShopFavorite\Repository\ShopFavoriteRepository;
use App\Models\Shop\Favorite;
use App\Models\Shop\Product;

class DbShopFavoriteInfrastructure implements ShopFavoriteRepository
{
    public function toggle(int $userId, int $productId): array
    {
        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found or inactive.'];
        }

        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return [
                'success' => true,
                'message' => 'Product removed from favorites.',
                'is_favorited' => false,
                'product_id' => $productId,
            ];
        }

        Favorite::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return [
            'success' => true,
            'message' => 'Product added to favorites.',
            'is_favorited' => true,
            'product_id' => $productId,
        ];
    }

    public function getList(int $userId): array
    {
        $favorites = Favorite::where('user_id', $userId)
            ->with(['product.brand:id,name,slug', 'product.images'])
            ->latest()
            ->get();

        return $favorites->map(function (Favorite $favorite) {
            $product = $favorite->product;

            if (!$product) {
                return null;
            }

            return [
                'id' => $favorite->id,
                'product_id' => $product->id,
                'added_at' => $favorite->created_at->toIso8601String(),
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'price_jpy' => $product->price_jpy,
                    'price_vnd' => $product->price_vnd,
                    'original_price_jpy' => $product->original_price_jpy,
                    'original_price_vnd' => $product->original_price_vnd,
                    'stock_quantity' => $product->stock_quantity,
                    'is_active' => $product->is_active,
                    'average_rating' => $product->average_rating,
                    'primary_image_url' => $product->primary_image_url,
                    'brand' => $product->brand ? [
                        'id' => $product->brand->id,
                        'name' => $product->brand->name,
                    ] : null,
                ],
            ];
        })->filter()->values()->toArray();
    }
}
