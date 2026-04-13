<?php

namespace App\Domain\ShopCollection\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopCollection\Repository\ShopCollectionRepository;
use App\Models\Shop\Collection;
use App\Models\Shop\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DbShopCollectionInfrastructure implements ShopCollectionRepository
{
    public function createCollection(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $collection = Collection::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . Str::random(6),
                'description' => $data['description'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (!empty($data['product_ids'])) {
                $syncData = [];
                foreach ($data['product_ids'] as $index => $productId) {
                    $syncData[$productId] = ['sort_order' => $index + 1];
                }
                $collection->products()->sync($syncData);
            }

            $collection->load(['products' => function ($query) {
                $query->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);
            }]);

            return [
                'message' => 'Collection created successfully.',
                'collection' => $this->formatCollection($collection),
            ];
        });
    }

    public function getCollections(): array
    {
        $collections = Collection::with(['products' => function ($query) {
            $query->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);
        }])
            ->orderBy('sort_order')
            ->get();

        return $collections->map(fn ($collection) => $this->formatCollection($collection))->toArray();
    }

    public function getActiveCollections(?int $userId = null): array
    {
        $collections = Collection::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);
            }])
            ->orderBy('sort_order')
            ->get();

        $favoritedIds = [];
        if ($userId) {
            $allProductIds = $collections->flatMap(fn($c) => $c->products->pluck('id'))->unique()->toArray();
            if (!empty($allProductIds)) {
                $favoritedIds = Favorite::where('user_id', $userId)
                    ->whereIn('product_id', $allProductIds)
                    ->pluck('product_id')
                    ->toArray();
            }
        }

        return $collections->map(fn ($collection) => $this->formatCollection($collection, $favoritedIds))->toArray();
    }

    public function getById(int $id): ?array
    {
        $collection = Collection::with(['products' => function ($query) {
            $query->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);
        }])->find($id);

        return $collection ? $this->formatCollection($collection) : null;
    }

    public function updateCollection(int $id, array $data): array
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return ['success' => false, 'message' => 'Collection not found.'];
        }

        return DB::transaction(function () use ($collection, $data) {
            if (!empty($data['name'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
            }

            $collection->update(array_filter([
                'name'        => $data['name'] ?? null,
                'slug'        => $data['slug'] ?? null,
                'description' => array_key_exists('description', $data) ? $data['description'] : null,
                'sort_order'  => $data['sort_order'] ?? null,
                'is_active'   => $data['is_active'] ?? null,
            ], fn ($v) => $v !== null));

            if (array_key_exists('product_ids', $data)) {
                $syncData = [];
                foreach ($data['product_ids'] as $index => $productId) {
                    $syncData[$productId] = ['sort_order' => $index + 1];
                }
                $collection->products()->sync($syncData);
            }

            $collection->load(['products' => function ($query) {
                $query->with(['brand:id,name,slug', 'category:id,name,slug', 'images']);
            }]);

            return [
                'success'    => true,
                'message'    => 'Collection updated successfully.',
                'collection' => $this->formatCollection($collection),
            ];
        });
    }

    public function deleteCollection(int $id): array
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return ['success' => false, 'message' => 'Collection not found.'];
        }

        $collection->products()->detach();
        $collection->delete();

        return [
            'success' => true,
            'message' => 'Collection deleted successfully.',
        ];
    }

    private function formatCollection(Collection $collection, array $favoritedIds = []): array
    {
        return [
            'id' => $collection->id,
            'name' => $collection->name,
            'slug' => $collection->slug,
            'description' => $collection->description,
            'sort_order' => $collection->sort_order,
            'is_active' => $collection->is_active,
            'products' => $collection->products->map(fn ($product) => $this->formatProduct($product, $favoritedIds))->toArray(),
            'created_at' => $collection->created_at?->toIso8601String(),
            'updated_at' => $collection->updated_at?->toIso8601String(),
        ];
    }

    private function formatProduct($product, array $favoritedIds = []): array
    {
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
            'stock_type' => $product->stock_type,
            'is_featured' => $product->is_featured,
            'is_domestic' => $product->is_domestic,
            'is_new' => $product->is_new,
            'sale_percent' => $product->sale_percent,
            'display_order' => $product->display_order,
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'sold_count' => $product->sold_count,
            'is_favorited' => in_array($product->id, $favoritedIds),
            'primary_image_url' => $product->primary_image ? CommonComponent::getFullUrl($product->primary_image) : null,
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
        ];
    }
}
