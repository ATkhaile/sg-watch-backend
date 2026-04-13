<?php

namespace App\Domain\ShopProduct\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopProduct\Repository\ShopProductRepository;
use App\Enums\InventoryHistoryType;
use App\Enums\OrderStatus;
use App\Models\Shop\Favorite;
use App\Models\Shop\InventoryHistory;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\ProductColor;
use App\Models\Shop\ProductImage;
use App\Services\FulfillWaitingOrdersService;
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

        // Filter by is_domestic
        if (isset($filters['is_domestic'])) {
            $query->where('is_domestic', $filters['is_domestic']);
        }

        // Filter by is_new
        if (isset($filters['is_new'])) {
            $query->where('is_new', $filters['is_new']);
        }

        // Group by
        if (!empty($filters['group_by']) && $filters['group_by'] === 'name') {
            $query->groupBy('name');
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'display_order':
                $query->orderBy('display_order', 'asc');
                break;
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

        $isGroupByName = !empty($filters['group_by']) && $filters['group_by'] === 'name';
        $groupedProductsByName = collect();
        if ($isGroupByName && count($paginator->items()) > 0) {
            $names = collect($paginator->items())->pluck('name')->unique()->toArray();
            $groupedProductsByName = Product::query()
                ->where('is_active', true)
                ->whereIn('name', $names)
                ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
                ->get()
                ->groupBy('name');
        }

        $favoritedProductIds = [];
        $purchasedProductIds = [];
        $productIds = collect($paginator->items())->pluck('id');
        if ($isGroupByName && $groupedProductsByName->isNotEmpty()) {
            $productIds = $productIds->merge($groupedProductsByName->flatten()->pluck('id'))->unique();
        }

        if ($userId) {
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

        $productsResult = [];
        foreach ($paginator->items() as $item) {
            if ($isGroupByName) {
                $groupItems = $groupedProductsByName->get($item->name, collect());
                $item->grouped_products = $groupItems->map(fn($v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'slug' => $v->slug,
                    'sku' => $v->sku,
                    'short_description' => $v->short_description,
                    'price_jpy' => $v->price_jpy,
                    'price_vnd' => $v->price_vnd,
                    'original_price_jpy' => $v->original_price_jpy,
                    'original_price_vnd' => $v->original_price_vnd,
                    'points' => $v->points,
                    'gender' => $v->gender,
                    'movement_type' => $v->movement_type,
                    'condition' => $v->condition,
                    'stock_quantity' => $v->stock_quantity,
                    'stock_type' => $v->stock_type,
                    'is_featured' => $v->is_featured,
                    'is_domestic' => $v->is_domestic,
                    'sale_percent' => $v->sale_percent,
                    'attributes' => $v->attributes,
                    'display_order' => $v->display_order,
                    'average_rating' => $v->average_rating,
                    'review_count' => $v->review_count,
                    'primary_image_url' => $v->primary_image_url,
                    'is_favorited' => in_array($v->id, $favoritedProductIds),
                    'is_purchased' => in_array($v->id, $purchasedProductIds),
                    'brand' => $v->brand ? ['id' => $v->brand->id, 'name' => $v->brand->name, 'slug' => $v->brand->slug] : null,
                    'category' => $v->category ? ['id' => $v->category->id, 'name' => $v->category->name, 'slug' => $v->category->slug] : null,
                ])->toArray();
            } else {
                $item->grouped_products = [];
            }
            $productsResult[] = $item;
        }

        return [
            'products' => $productsResult,
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
            'is_domestic' => $product->is_domestic,
            'is_new' => $product->is_new,
            'sale_percent' => $product->sale_percent,
            'attributes' => $product->attributes,
            'display_order' => $product->display_order,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'sold_count' => $product->sold_count,
            'primary_image_url' => $product->primary_image_url,
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
            ->with(['brand:id,name,slug,logo_url,country', 'category:id,name,slug,parent_id', 'images', 'colors.images', 'approvedReviews.user:id,first_name,last_name'])
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
            'primary_image_url' => $product->primary_image_url,
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
            'is_domestic' => $product->is_domestic,
            'is_new' => $product->is_new,
            'sale_percent' => $product->sale_percent,
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
                'sort_order' => $img->sort_order,
            ])->toArray(),
            'colors' => $product->colors->map(fn ($color) => $this->formatColor($color))->toArray(),
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

        // Filter by is_domestic
        if (isset($filters['is_domestic'])) {
            $query->where('is_domestic', $filters['is_domestic']);
        }

        // Filter by is_new
        if (isset($filters['is_new'])) {
            $query->where('is_new', $filters['is_new']);
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

        // Group by
        if (!empty($filters['group_by']) && $filters['group_by'] === 'name') {
            $query->groupBy('name');
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        match ($sortBy) {
            'display_order' => $query->orderBy('display_order', 'asc'),
            'price_asc' => $query->orderBy('price_jpy', 'asc'),
            'price_desc' => $query->orderBy('price_jpy', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        $isGroupByName = !empty($filters['group_by']) && $filters['group_by'] === 'name';
        $groupedProductsByName = collect();
        if ($isGroupByName && count($paginator->items()) > 0) {
            $names = collect($paginator->items())->pluck('name')->unique()->toArray();
            $groupedProductsByName = Product::query()
                ->whereIn('name', $names)
                ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
                ->get()
                ->groupBy('name');
        }

        $productIds = collect($paginator->items())->pluck('id');
        if ($isGroupByName && $groupedProductsByName->isNotEmpty()) {
            $productIds = $productIds->merge($groupedProductsByName->flatten()->pluck('id'))->unique();
        }

        $purchasedProductIds = DB::table('shop_order_items')
            ->join('shop_orders', 'shop_orders.id', '=', 'shop_order_items.order_id')
            ->whereIn('shop_orders.status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
            ->whereIn('shop_order_items.product_id', $productIds)
            ->distinct()
            ->pluck('shop_order_items.product_id')
            ->toArray();

        return [
            'products' => collect($paginator->items())->map(function ($product) use ($purchasedProductIds, $isGroupByName, $groupedProductsByName) {
                $formatted = array_merge(
                    $this->formatProduct($product),
                    ['is_purchased' => in_array($product->id, $purchasedProductIds)]
                );

                if ($isGroupByName) {
                    $variants = $groupedProductsByName->get($product->name, collect());
                    $formatted['grouped_products'] = $variants->map(fn($v) => array_merge(
                        $this->formatProduct($v),
                        ['is_purchased' => in_array($v->id, $purchasedProductIds)]
                    ))->toArray();
                } else {
                    $formatted['grouped_products'] = [];
                }

                return $formatted;
            })->toArray(),
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
        $product = Product::with(['brand:id,name,slug,logo_url,country', 'category:id,name,slug,parent_id', 'images', 'colors.images', 'approvedReviews.user:id,first_name,last_name'])
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
            $primaryImage = $data['primary_image'] ?? null;
            unset($data['images'], $data['primary_image']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
            }

            $product = Product::create($data);

            if ($primaryImage instanceof UploadedFile) {
                $product->update([
                    'primary_image' => $primaryImage->store('products/' . $product->id, 'public'),
                ]);
            }

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
            $primaryImage = $data['primary_image'] ?? null;
            unset($data['images'], $data['existing_image_ids'], $data['primary_image']);

            if (isset($data['slug']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name'] ?? $product->name) . '-' . Str::random(6);
            }

            if ($primaryImage instanceof UploadedFile) {
                // Delete old primary image
                if ($product->primary_image) {
                    Storage::disk('public')->delete($product->primary_image);
                }
                $data['primary_image'] = $primaryImage->store('products/' . $product->id, 'public');
            }

            // Record import history when admin manually increases stock
            $stockIncreased = isset($data['stock_quantity']) && (int) $data['stock_quantity'] > (int) $product->stock_quantity;
            if ($stockIncreased) {
                InventoryHistory::create([
                    'product_id'     => $product->id,
                    'type'           => InventoryHistoryType::IMPORT,
                    'quantity'       => (int) $data['stock_quantity'] - (int) $product->stock_quantity,
                    'stock_before'   => (int) $product->stock_quantity,
                    'stock_after'    => (int) $data['stock_quantity'],
                    'reference_type' => 'admin_update',
                    'reference_id'   => null,
                ]);
            }

            $product->update($data);

            if ($stockIncreased) {
                FulfillWaitingOrdersService::fulfill($product->id);
            }

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
                    foreach ($newImages as $index => $image) {
                        if ($image instanceof UploadedFile) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_url' => $image->store('products/' . $product->id, 'public'),
                                'alt_text' => null,
                                'is_primary' => false,
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

    public function deleteByBrand(int $brandId): array
    {
        $count = Product::where('brand_id', $brandId)->whereNull('deleted_at')->count();

        if ($count === 0) {
            return [
                'success'       => true,
                'message'       => 'No active products found for this brand.',
                'deleted_count' => 0,
            ];
        }

        Product::where('brand_id', $brandId)->whereNull('deleted_at')->update(['deleted_at' => now()]);

        return [
            'success'       => true,
            'message'       => "Soft deleted {$count} product(s) for brand ID {$brandId}.",
            'deleted_count' => $count,
        ];
    }

    public function restoreByBrand(int $brandId): array
    {
        $query = Product::withTrashed()->where('brand_id', $brandId)->whereNotNull('deleted_at');
        $count = $query->count();

        if ($count === 0) {
            return [
                'success'        => true,
                'message'        => 'No deleted products found for this brand.',
                'restored_count' => 0,
            ];
        }

        $query->update(['deleted_at' => null]);

        return [
            'success'        => true,
            'message'        => "Restored {$count} product(s) for brand ID {$brandId}.",
            'restored_count' => $count,
        ];
    }


    public function updateProductSortOrder(int $productId, int $newDisplayOrder): array
    {
        return DB::transaction(function () use ($productId, $newDisplayOrder) {
            $product = Product::findOrFail($productId);
            $oldDisplayOrder = $product->display_order;

            // Find the product currently at the target position within the same brand/category
            $scopeQuery = $product->brand_id
                ? Product::where('brand_id', $product->brand_id)
                : Product::where('category_id', $product->category_id);
            $targetProduct = $scopeQuery->where('display_order', $newDisplayOrder)->first();

            if ($targetProduct) {
                // Use temporary value to avoid unique constraint violation
                $product->update(['display_order' => 0]);
                $targetProduct->update(['display_order' => $oldDisplayOrder]);
            }

            $product->update(['display_order' => $newDisplayOrder]);

            return [
                'message' => 'Product sort order updated successfully.',
            ];
        });
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

    public function getFeaturedProducts(?int $userId = null): array
    {
        $products = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['brand:id,name,slug', 'category:id,name,slug', 'images'])
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $favoritedProductIds = [];
        if ($userId) {
            $productIds = $products->pluck('id')->toArray();
            $favoritedProductIds = Favorite::where('user_id', $userId)
                ->whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->toArray();
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
            'is_domestic' => $product->is_domestic,
            'is_new' => $product->is_new,
            'sale_percent' => $product->sale_percent,
            'attributes' => $product->attributes,
            'display_order' => $product->display_order,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'sold_count' => $product->sold_count,
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
        ])->toArray();
    }

    private function syncImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            // Handle file upload or URL string
            if ($image instanceof UploadedFile) {
                $imageUrl = $image->store('products/' . $product->id, 'public');
                $altText = null;
                $sortOrder = $index;
            } elseif (is_array($image)) {
                if (isset($image['image_url']) && $image['image_url'] instanceof UploadedFile) {
                    $imageUrl = $image['image_url']->store('products/' . $product->id, 'public');
                } else {
                    $imageUrl = $image['image_url'] ?? '';
                }
                $altText = $image['alt_text'] ?? null;
                $sortOrder = $image['sort_order'] ?? $index;
            } else {
                continue;
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imageUrl,
                'alt_text' => $altText,
                'is_primary' => false,
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
            'primary_image_url' => $product->primary_image ? CommonComponent::getFullUrl($product->primary_image) : null,
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
            'is_domestic' => $product->is_domestic,
            'is_new' => $product->is_new,
            'sale_percent' => $product->sale_percent,
            'sort_order' => $product->sort_order,
            'display_order' => $product->display_order,
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
                'sort_order' => $img->sort_order,
            ])->toArray(),
            'colors' => $product->relationLoaded('colors')
                ? $product->colors->map(fn ($color) => $this->formatColor($color))->toArray()
                : [],
            'created_at' => $product->created_at?->toIso8601String(),
            'updated_at' => $product->updated_at?->toIso8601String(),
        ];
    }

    private function formatColor(ProductColor $color): array
    {
        return [
            'id' => $color->id,
            'product_id' => $color->product_id,
            'color_code' => $color->color_code,
            'color_name' => $color->color_name,
            'sku' => $color->sku,
            'price_jpy' => $color->price_jpy,
            'price_vnd' => $color->price_vnd,
            'original_price_jpy' => $color->original_price_jpy,
            'original_price_vnd' => $color->original_price_vnd,
            'sale_percent' => $color->sale_percent,
            'points' => $color->points,
            'stock_quantity' => $color->stock_quantity,
            'is_active' => $color->is_active,
            'sort_order' => $color->sort_order,
            'images' => $color->relationLoaded('images')
                ? $color->images->map(fn ($img) => [
                    'id' => $img->id,
                    'image_url' => $img->image_url ? CommonComponent::getFullUrl($img->image_url) : null,
                    'alt_text' => $img->alt_text,
                    'is_primary' => $img->is_primary,
                    'sort_order' => $img->sort_order,
                ])->toArray()
                : [],
        ];
    }
}
