<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProductListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $favoritedProductIds = $this->resource['favorited_product_ids'] ?? [];

        return [
            'message' => 'Products retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'products' => collect($this->resource['products'])->map(function ($product) use ($favoritedProductIds) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'sku' => $product->sku,
                        'short_description' => $product->short_description,
                        'price_jpy' => $product->price_jpy,
                        'price_vnd' => $product->price_vnd,
                        'original_price_jpy' => $product->original_price_jpy,
                        'original_price_vnd' => $product->original_price_vnd,
                        'points' => $product->points,
                        'gender' => $product->gender,
                        'movement_type' => $product->movement_type,
                        'condition' => $product->condition,
                        'stock_quantity' => $product->stock_quantity,
                        'is_featured' => $product->is_featured,
                        'average_rating' => $product->average_rating,
                        'review_count' => $product->review_count,
                        'primary_image_url' => $product->primary_image_url,
                        'is_favorited' => in_array($product->id, $favoritedProductIds),
                        'brand' => $product->brand ? [
                            'id' => $product->brand->id,
                            'name' => $product->brand->name,
                            'slug' => $product->brand->slug,
                        ] : null,
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'name' => $product->category->name,
                            'slug' => $product->category->slug,
                        ] : null,
                    ];
                }),
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
