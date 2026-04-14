<?php

namespace App\Domain\BigSale\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\BigSale\Repository\BigSaleRepository;
use App\Models\BigSale;
use App\Models\Shop\Favorite;
use App\Models\Shop\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DbBigSaleInfrastructure implements BigSaleRepository
{
    public function getList(array $filters): array
    {
        $query = BigSale::query();

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at_desc';
        match ($sortBy) {
            'created_at_asc' => $query->orderBy('created_at', 'asc'),
            'sale_start_date_asc' => $query->orderBy('sale_start_date', 'asc'),
            'sale_start_date_desc' => $query->orderBy('sale_start_date', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'big_sales' => collect($paginator->items())->map(fn($bigSale) => $this->formatBigSale($bigSale))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getById(int $id): ?array
    {
        $bigSale = BigSale::find($id);
        if (!$bigSale) {
            return null;
        }
        return $this->formatBigSaleDetail($bigSale);
    }

    public function create(array $data): array
    {
        $media = $data['media'] ?? null;
        unset($data['media']);

        if ($media instanceof UploadedFile) {
            $path = $media->store('big_sales', 'public');
            $data['media_url'] = $path;
            $data['media_type'] = $this->detectMediaType($media);
        }

        $bigSale = BigSale::create($data);

        return [
            'success' => true,
            'message' => 'Big sale created successfully.',
            'big_sale' => $this->formatBigSaleDetail($bigSale),
        ];
    }

    public function update(int $id, array $data): array
    {
        $bigSale = BigSale::find($id);
        if (!$bigSale) {
            return ['success' => false, 'message' => 'Big sale not found.'];
        }

        $media = $data['media'] ?? null;
        unset($data['media']);

        $bigSale->update($data);

        if ($media instanceof UploadedFile) {
            if ($bigSale->media_url && Storage::disk('public')->exists($bigSale->media_url)) {
                Storage::disk('public')->delete($bigSale->media_url);
            }
            $path = $media->store('big_sales/' . $bigSale->id, 'public');
            $bigSale->update([
                'media_url' => $path,
                'media_type' => $this->detectMediaType($media),
            ]);
        }

        return [
            'success' => true,
            'message' => 'Big sale updated successfully.',
            'big_sale' => $this->formatBigSaleDetail($bigSale->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $bigSale = BigSale::find($id);
        if (!$bigSale) {
            return ['success' => false, 'message' => 'Big sale not found.', 'status_code' => 404];
        }

        $bigSale->delete();

        return ['success' => true, 'message' => 'Big sale deleted successfully.', 'status_code' => 200];
    }

    public function getPublicList(array $filters): array
    {
        $now = now()->toDateString();

        $query = BigSale::where('is_active', true)
            ->where('sale_start_date', '<=', $now)
            ->where('sale_end_date', '>=', $now)
            ->orderBy('sale_start_date', 'desc');

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'big_sales' => collect($paginator->items())->map(fn($bigSale) => $this->formatBigSale($bigSale))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getPublicDetail(int $id, ?int $userId = null): ?array
    {
        $now = now()->toDateString();

        $bigSale = BigSale::where('is_active', true)
            ->where('sale_start_date', '<=', $now)
            ->where('sale_end_date', '>=', $now)
            ->find($id);

        if (!$bigSale) {
            return null;
        }

        return $this->formatBigSaleDetail($bigSale, $userId);
    }

    private function formatBigSale(BigSale $bigSale): array
    {
        return [
            'id' => $bigSale->id,
            'title' => $bigSale->title,
            'description' => $bigSale->description,
            'media_url' => $bigSale->media_full_url,
            'media_type' => $bigSale->media_type,
            'product_ids' => $bigSale->product_ids,
            'sale_start_date' => $bigSale->sale_start_date?->format('Y-m-d'),
            'sale_end_date' => $bigSale->sale_end_date?->format('Y-m-d'),
            'sale_percentage' => $bigSale->sale_percentage,
            'is_active' => $bigSale->is_active,
            'created_at' => $bigSale->created_at?->toIso8601String(),
            'updated_at' => $bigSale->updated_at?->toIso8601String(),
        ];
    }

    private function formatBigSaleDetail(BigSale $bigSale, ?int $userId = null): array
    {
        $data = $this->formatBigSale($bigSale);

        $products = Product::whereIn('id', $bigSale->product_ids ?? [])
            ->with(['category', 'brand'])
            ->get();

        $favoritedIds = [];
        if ($userId && $products->isNotEmpty()) {
            $favoritedIds = Favorite::where('user_id', $userId)
                ->whereIn('product_id', $products->pluck('id')->toArray())
                ->pluck('product_id')
                ->toArray();
        }

        $data['products'] = $products->map(function ($product) use ($favoritedIds) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price_jpy' => $product->price_jpy,
                'price_vnd' => $product->price_vnd,
                'original_price_jpy' => $product->original_price_jpy,
                'original_price_vnd' => $product->original_price_vnd,
                'sale_percent' => $product->sale_percent,
                'average_rating' => $product->average_rating,
                'review_count' => $product->review_count,
                'primary_image_url' => $product->primary_image_url,
                'is_favorited' => in_array($product->id, $favoritedIds),
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ] : null,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name,
                ] : null,
            ];
        })->toArray();

        return $data;
    }

    private function detectMediaType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        return 'image';
    }
}
