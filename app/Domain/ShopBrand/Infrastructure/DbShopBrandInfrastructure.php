<?php

namespace App\Domain\ShopBrand\Infrastructure;

use App\Domain\ShopBrand\Repository\ShopBrandRepository;
use App\Models\Shop\Brand;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DbShopBrandInfrastructure implements ShopBrandRepository
{
    public function getList(array $filters): array
    {
        $query = Brand::query();

        if (isset($filters['keyword']) && $filters['keyword'] !== '') {
            $keyword = '%' . $filters['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', $keyword)
                  ->orWhere('description', 'like', $keyword);
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $sortBy = $filters['sort_by'] ?? 'sort_order_asc';
        match ($sortBy) {
            'sort_order_asc' => $query->orderBy('sort_order', 'asc'),
            'sort_order_desc' => $query->orderBy('sort_order', 'desc'),
            'created_at_asc' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'brands' => collect($paginator->items())->map(fn($brand) => $this->formatBrand($brand))->toArray(),
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
        $brand = Brand::find($id);
        if (!$brand) {
            return null;
        }
        return $this->formatBrand($brand);
    }

    public function create(array $data): array
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        if ($image instanceof UploadedFile) {
            $path = $image->store('shop_brands', 'public');
            $data['logo_url'] = $path;
        }

        $brand = Brand::create($data);

        return [
            'success' => true,
            'message' => 'Shop brand created successfully.',
            'brand' => $this->formatBrand($brand),
        ];
    }

    public function update(int $id, array $data): array
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return ['success' => false, 'message' => 'Shop brand not found.'];
        }

        $image = $data['image'] ?? null;
        unset($data['image']);

        $brand->update($data);

        if ($image instanceof UploadedFile) {
            if ($brand->logo_url && Storage::disk('public')->exists($brand->logo_url)) {
                Storage::disk('public')->delete($brand->logo_url);
            }
            $path = $image->store('shop_brands/' . $brand->id, 'public');
            $brand->update(['logo_url' => $path]);
        }

        return [
            'success' => true,
            'message' => 'Shop brand updated successfully.',
            'brand' => $this->formatBrand($brand->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return ['success' => false, 'message' => 'Shop brand not found.', 'status_code' => 404];
        }

        $brand->delete();

        return ['success' => true, 'message' => 'Shop brand deleted successfully.', 'status_code' => 200];
    }

    private function formatBrand(Brand $brand): array
    {
        return [
            'id' => $brand->id,
            'name' => $brand->name,
            'slug' => $brand->slug,
            'logo_url' => $brand->logo_full_url,
            'description' => $brand->description,
            'country' => $brand->country,
            'is_active' => $brand->is_active,
            'sort_order' => $brand->sort_order,
            'created_at' => $brand->created_at?->toIso8601String(),
            'updated_at' => $brand->updated_at?->toIso8601String(),
        ];
    }
}
