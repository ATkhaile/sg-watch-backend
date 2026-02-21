<?php

namespace App\Domain\ShopProduct\Infrastructure;

use App\Domain\ShopProduct\Repository\ShopProductRepository;
use App\Models\Shop\Product;

class DbShopProductInfrastructure implements ShopProductRepository
{
    public function getList(array $filters): array
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);

        // Search by name
        if (!empty($filters['keyword'])) {
            $keyword = '%' . $filters['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', $keyword)
                  ->orWhere('sku', 'like', $keyword);
            });
        }

        // Filter by gender
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // Filter by brand
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        // Filter by movement type
        if (!empty($filters['movement_type'])) {
            $query->where('movement_type', $filters['movement_type']);
        }

        // Filter by in stock
        if (isset($filters['in_stock'])) {
            if ($filters['in_stock']) {
                $query->where('stock_quantity', '>', 0);
            } else {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        // Filter by category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price_jpy', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_jpy', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'products' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getBySlug(string $slug): ?array
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['brand:id,name,slug,logo_url,country', 'category:id,name,slug,parent_id', 'images', 'approvedReviews.user:id,first_name,last_name'])
            ->first();

        if (!$product) {
            return null;
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'product_info' => $product->product_info,
            'deal_info' => $product->deal_info,
            'price_jpy' => $product->price_jpy,
            'price_vnd' => $product->price_vnd,
            'original_price_jpy' => $product->original_price_jpy,
            'original_price_vnd' => $product->original_price_vnd,
            'points' => $product->points,
            'gender' => $product->gender,
            'movement_type' => $product->movement_type,
            'condition' => $product->condition,
            'attributes' => $product->attributes,
            'stock_quantity' => $product->stock_quantity,
            'warranty_months' => $product->warranty_months,
            'is_featured' => $product->is_featured,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'view_count' => $product->view_count,
            'sold_count' => $product->sold_count,
            'brand' => $product->brand ? [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
                'slug' => $product->brand->slug,
                'logo_url' => $product->brand->logo_url,
                'country' => $product->brand->country,
            ] : null,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
                'parent_id' => $product->category->parent_id,
            ] : null,
            'images' => $product->images->map(fn ($img) => [
                'id' => $img->id,
                'image_url' => $img->image_url,
                'is_primary' => $img->is_primary,
                'sort_order' => $img->sort_order,
            ])->toArray(),
            'reviews' => $product->approvedReviews->map(fn ($review) => [
                'id' => $review->id,
                'rating' => $review->rating,
                'title' => $review->title,
                'comment' => $review->comment,
                'user' => $review->user ? [
                    'first_name' => $review->user->first_name,
                    'last_name' => $review->user->last_name,
                ] : null,
                'created_at' => $review->created_at?->format('Y-m-d H:i:s'),
            ])->toArray(),
        ];
    }
}
