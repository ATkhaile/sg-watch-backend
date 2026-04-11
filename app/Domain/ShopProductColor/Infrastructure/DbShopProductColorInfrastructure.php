<?php

namespace App\Domain\ShopProductColor\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\ShopProductColor\Repository\ShopProductColorRepository;
use App\Models\Shop\ProductColor;
use App\Models\Shop\ProductColorImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DbShopProductColorInfrastructure implements ShopProductColorRepository
{
    public function getByProductId(int $productId): array
    {
        $colors = ProductColor::where('product_id', $productId)
            ->with('images')
            ->orderBy('sort_order')
            ->get();

        return [
            'colors' => $colors->map(fn ($color) => $this->formatColor($color))->toArray(),
        ];
    }

    public function getById(int $id): ?array
    {
        $color = ProductColor::with('images')->find($id);

        if (!$color) {
            return null;
        }

        return $this->formatColor($color);
    }

    public function create(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $images = $data['images'] ?? [];
            unset($data['images']);

            $color = ProductColor::create($data);

            $this->syncImages($color, $images);

            $color->load('images');

            return [
                'success' => true,
                'message' => 'Product color created successfully.',
                'color' => $this->formatColor($color),
            ];
        });
    }

    public function update(int $id, array $data): array
    {
        $color = ProductColor::find($id);

        if (!$color) {
            return ['success' => false, 'message' => 'Product color not found.'];
        }

        return DB::transaction(function () use ($color, $data) {
            $newImages = $data['images'] ?? null;
            $existingImageIds = $data['existing_image_ids'] ?? null;
            unset($data['images'], $data['existing_image_ids']);

            $color->update($data);

            if ($existingImageIds !== null || $newImages !== null) {
                if ($existingImageIds !== null) {
                    $imagesToDelete = $color->images()
                        ->whereNotIn('id', $existingImageIds)
                        ->get();
                    foreach ($imagesToDelete as $img) {
                        Storage::disk('public')->delete($img->image_url);
                        $img->delete();
                    }
                }

                if ($newImages) {
                    $this->syncImages($color, $newImages);
                }
            }

            $color->load('images');

            return [
                'success' => true,
                'message' => 'Product color updated successfully.',
                'color' => $this->formatColor($color),
            ];
        });
    }

    public function delete(int $id): array
    {
        $color = ProductColor::find($id);

        if (!$color) {
            return ['success' => false, 'message' => 'Product color not found.'];
        }

        $color->delete();

        return [
            'success' => true,
            'message' => 'Product color deleted successfully.',
        ];
    }

    private function syncImages(ProductColor $color, array $images): void
    {
        $startSort = ($color->images()->max('sort_order') ?? -1) + 1;

        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $imageUrl = $image->store('products/' . $color->product_id . '/colors/' . $color->id, 'public');
                $altText = null;
                $isPrimary = false;
                $sortOrder = $startSort + $index;
            } elseif (is_array($image)) {
                if (isset($image['image_url']) && $image['image_url'] instanceof UploadedFile) {
                    $imageUrl = $image['image_url']->store('products/' . $color->product_id . '/colors/' . $color->id, 'public');
                } else {
                    $imageUrl = $image['image_url'] ?? '';
                }
                $altText = $image['alt_text'] ?? null;
                $isPrimary = $image['is_primary'] ?? false;
                $sortOrder = $image['sort_order'] ?? ($startSort + $index);
            } else {
                continue;
            }

            ProductColorImage::create([
                'product_color_id' => $color->id,
                'image_url' => $imageUrl,
                'alt_text' => $altText,
                'is_primary' => $isPrimary,
                'sort_order' => $sortOrder,
            ]);
        }
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
            'cost_price_jpy' => $color->cost_price_jpy,
            'sale_percent' => $color->sale_percent,
            'points' => $color->points,
            'stock_quantity' => $color->stock_quantity,
            'is_active' => $color->is_active,
            'sort_order' => $color->sort_order,
            'images' => $color->images->map(fn ($img) => [
                'id' => $img->id,
                'image_url' => $img->image_url ? CommonComponent::getFullUrl($img->image_url) : null,
                'alt_text' => $img->alt_text,
                'is_primary' => $img->is_primary,
                'sort_order' => $img->sort_order,
            ])->toArray(),
            'created_at' => $color->created_at?->toIso8601String(),
            'updated_at' => $color->updated_at?->toIso8601String(),
        ];
    }
}
