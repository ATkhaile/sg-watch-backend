<?php

namespace App\Domain\ShopProduct\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopProduct\Repository\ShopProductRepository;
use App\Enums\OrderStatus;
use App\Models\Shop\Favorite;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DbShopProductInfrastructure implements ShopProductRepository
{
    public function getList(array $filters, ?int $userId = null): array
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

        // Filter by stock type
        if (!empty($filters['stock_type'])) {
            $query->where('stock_type', $filters['stock_type']);
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

        $favoritedProductIds = [];
        $purchasedProductIds = [];
        if ($userId) {
            $productIds = collect($paginator->items())->pluck('id');
            $favoritedProductIds = Favorite::where('user_id', $userId)
                ->whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->toArray();
            $purchasedProductIds = Order::where('user_id', $userId)
                ->whereIn('status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
                ->whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))
                ->with(['items:id,order_id,product_id'])
                ->get()
                ->flatMap(fn ($order) => $order->items->pluck('product_id'))
                ->unique()
                ->values()
                ->toArray();
        }

        return [
            'products' => $paginator->items(),
            'favorited_product_ids' => $favoritedProductIds,
            'purchased_product_ids' => $purchasedProductIds,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getBestSellers(int $limit): array
    {
        // Get top sellers
        $products = Product::query()
            ->where('is_active', true)
            ->where('sold_count', '>', 0)
            ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
            ->orderByDesc('sold_count')
            ->limit($limit)
            ->get();

        // Fill remaining slots with random products
        if ($products->count() < $limit) {
            $excludeIds = $products->pluck('id')->toArray();
            $remaining = Product::query()
                ->where('is_active', true)
                ->whereNotIn('id', $excludeIds)
                ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
                ->inRandomOrder()
                ->limit($limit - $products->count())
                ->get();

            $products = $products->concat($remaining);
        }

        return $products->map(fn ($product) => [
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
            'stock_type' => $product->stock_type,
            'is_featured' => $product->is_featured,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'sold_count' => $product->sold_count,
            'primary_image_url' => $product->primary_image_url ? CommonComponent::getFullUrl($product->primary_image_url) : null,
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
        ])->toArray();
    }

    public function getBySlug(string $slug, ?int $userId = null): ?array
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['brand:id,name,slug,logo_url,country', 'category:id,name,slug,parent_id', 'images', 'approvedReviews.user:id,first_name,last_name'])
            ->first();

        if (!$product) {
            return null;
        }

        $isFavorited = false;
        $isPurchased = false;
        if ($userId) {
            $isFavorited = Favorite::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->exists();
            $isPurchased = Order::where('user_id', $userId)
                ->whereIn('status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->exists();
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
            'stock_type' => $product->stock_type,
            'warranty_months' => $product->warranty_months,
            'is_featured' => $product->is_featured,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'view_count' => $product->view_count,
            'sold_count' => $product->sold_count,
            'is_favorited' => $isFavorited,
            'is_purchased' => $isPurchased,
            'brand' => $product->brand ? [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
                'slug' => $product->brand->slug,
                'logo_url' => $product->brand->logo_url ? CommonComponent::getFullUrl($product->brand->logo_url) : null,
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
                'image_url' => $img->image_url ? CommonComponent::getFullUrl($img->image_url) : null,
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

    public function adminGetList(array $filters): array
    {
        $query = Product::query()
            ->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);

        // Search by name/sku
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

        // Filter by category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filter by movement type
        if (!empty($filters['movement_type'])) {
            $query->where('movement_type', $filters['movement_type']);
        }

        // Filter by is_active
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Filter by in stock
        if (isset($filters['in_stock'])) {
            if ($filters['in_stock']) {
                $query->where('stock_quantity', '>', 0);
            } else {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        // Filter by stock type
        if (!empty($filters['stock_type'])) {
            $query->where('stock_type', $filters['stock_type']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        match ($sortBy) {
            'price_asc' => $query->orderBy('price_jpy', 'asc'),
            'price_desc' => $query->orderBy('price_jpy', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        $productIds = collect($paginator->items())->pluck('id');
        $purchasedProductIds = DB::table('shop_order_items')
            ->join('shop_orders', 'shop_orders.id', '=', 'shop_order_items.order_id')
            ->whereIn('shop_orders.status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
            ->whereIn('shop_order_items.product_id', $productIds)
            ->distinct()
            ->pluck('shop_order_items.product_id')
            ->toArray();

        return [
            'products' => collect($paginator->items())->map(fn ($product) => array_merge(
                $this->formatProduct($product),
                ['is_purchased' => in_array($product->id, $purchasedProductIds)]
            ))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function adminGetById(int $id): ?array
    {
        $product = Product::with(['brand:id,name,slug,logo_url,country', 'category:id,name,slug,parent_id', 'images', 'approvedReviews.user:id,first_name,last_name'])
            ->find($id);

        if (!$product) {
            return null;
        }

        $isPurchased = Order::whereIn('status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->exists();

        return array_merge($this->formatProduct($product), ['is_purchased' => $isPurchased]);
    }

    public function create(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $images = $data['images'] ?? [];
            unset($data['images']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
            }

            $product = Product::create($data);

            $this->syncImages($product, $images);

            $product->load(['brand:id,name,slug', 'category:id,name,slug', 'images']);

            return [
                'success' => true,
                'message' => 'Product created successfully.',
                'product' => $this->formatProduct($product),
            ];
        });
    }

    public function update(int $id, array $data): array
    {
        $product = Product::find($id);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        return DB::transaction(function () use ($product, $data) {
            $newImages = $data['images'] ?? null;
            $existingImageIds = $data['existing_image_ids'] ?? [];
            unset($data['images'], $data['existing_image_ids']);

            if (isset($data['slug']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name'] ?? $product->name) . '-' . Str::random(6);
            }

            $product->update($data);

            if ($existingImageIds !== null || $newImages !== null) {
                // Delete images not in the keep list
                if ($existingImageIds !== null) {
                    $imagesToDelete = $product->images()
                        ->whereNotIn('id', $existingImageIds)
                        ->get();
                    foreach ($imagesToDelete as $img) {
                        Storage::disk('public')->delete($img->image_url);
                        $img->delete();
                    }
                }

                // Upload new images
                if ($newImages) {
                    $startSort = ($product->images()->max('sort_order') ?? -1) + 1;
                    $hasPrimary = $product->images()->where('is_primary', true)->exists();
                    foreach ($newImages as $index => $image) {
                        if ($image instanceof UploadedFile) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_url' => $image->store('products/' . $product->id, 'public'),
                                'alt_text' => null,
                                'is_primary' => !$hasPrimary && $index === 0,
                                'sort_order' => $startSort + $index,
                            ]);
                        }
                    }
                }
            }

            $product->load(['brand:id,name,slug', 'category:id,name,slug', 'images']);

            return [
                'success' => true,
                'message' => 'Product updated successfully.',
                'product' => $this->formatProduct($product),
            ];
        });
    }

    public function delete(int $id): array
    {
        $product = Product::find($id);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        $product->delete();

        return [
            'success' => true,
            'message' => 'Product deleted successfully.',
        ];
    }

    public function updateFeaturedProducts(array $productIds): array
    {
        return DB::transaction(function () use ($productIds) {
            // Clear all featured flags
            Product::where('is_featured', true)->update(['is_featured' => false, 'sort_order' => 0]);

            // Set featured + sort_order for selected products
            foreach ($productIds as $index => $id) {
                Product::where('id', $id)->update(['is_featured' => true, 'sort_order' => $index + 1]);
            }

            // Return updated featured list
            $products = Product::whereIn('id', $productIds)
                ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
                ->orderBy('sort_order')
                ->get();

            return [
                'message' => 'Featured products updated successfully.',
                'products' => $products->map(fn ($product) => $this->formatProduct($product))->toArray(),
            ];
        });
    }

    public function getFeaturedProducts(): array
    {
        $products = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return $products->map(fn ($product) => [
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
            'stock_type' => $product->stock_type,
            'is_featured' => $product->is_featured,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'sold_count' => $product->sold_count,
            'primary_image_url' => $product->primary_image_url ? CommonComponent::getFullUrl($product->primary_image_url) : null,
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
        ])->toArray();
    }

    private function syncImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            // Handle file upload or URL string
            if ($image instanceof UploadedFile) {
                $imageUrl = $image->store('products/' . $product->id, 'public');
                $altText = null;
                $isPrimary = $index === 0;
                $sortOrder = $index;
            } elseif (is_array($image)) {
                if (isset($image['image_url']) && $image['image_url'] instanceof UploadedFile) {
                    $imageUrl = $image['image_url']->store('products/' . $product->id, 'public');
                } else {
                    $imageUrl = $image['image_url'] ?? '';
                }
                $altText = $image['alt_text'] ?? null;
                $isPrimary = $image['is_primary'] ?? ($index === 0);
                $sortOrder = $image['sort_order'] ?? $index;
            } else {
                continue;
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imageUrl,
                'alt_text' => $altText,
                'is_primary' => $isPrimary,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function formatProduct(Product $product): array
    {
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
            'cost_price_jpy' => $product->cost_price_jpy,
            'points' => $product->points,
            'gender' => $product->gender,
            'movement_type' => $product->movement_type,
            'condition' => $product->condition,
            'attributes' => $product->attributes,
            'stock_quantity' => $product->stock_quantity,
            'stock_type' => $product->stock_type,
            'warranty_months' => $product->warranty_months,
            'is_active' => $product->is_active,
            'is_featured' => $product->is_featured,
            'sort_order' => $product->sort_order,
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
            'images' => $product->images->map(fn ($img) => [
                'id' => $img->id,
                'image_url' => $img->image_url ? CommonComponent::getFullUrl($img->image_url) : null,
                'alt_text' => $img->alt_text,
                'is_primary' => $img->is_primary,
                'sort_order' => $img->sort_order,
            ])->toArray(),
            'created_at' => $product->created_at?->toIso8601String(),
            'updated_at' => $product->updated_at?->toIso8601String(),
        ];
    }
}
