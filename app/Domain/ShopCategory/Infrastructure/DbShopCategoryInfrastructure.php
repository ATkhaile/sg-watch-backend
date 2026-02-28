<?php

namespace App\Domain\ShopCategory\Infrastructure;

use App\Domain\ShopCategory\Repository\ShopCategoryRepository;
use App\Models\Shop\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DbShopCategoryInfrastructure implements ShopCategoryRepository
{
    public function getList(array $filters): array
    {
        $query = Category::query();

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

        if (isset($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
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
            'categories' => collect($paginator->items())->map(fn($category) => $this->formatCategory($category))->toArray(),
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
        $category = Category::find($id);
        if (!$category) {
            return null;
        }
        return $this->formatCategory($category);
    }

    public function create(array $data): array
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        if ($image instanceof UploadedFile) {
            $path = $image->store('shop_categories', 'public');
            $data['image_url'] = $path;
        }

        $category = Category::create($data);

        return [
            'success' => true,
            'message' => 'Shop category created successfully.',
            'category' => $this->formatCategory($category),
        ];
    }

    public function update(int $id, array $data): array
    {
        $category = Category::find($id);
        if (!$category) {
            return ['success' => false, 'message' => 'Shop category not found.'];
        }

        $image = $data['image'] ?? null;
        unset($data['image']);

        $category->update($data);

        if ($image instanceof UploadedFile) {
            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete($category->image_url);
            }
            $path = $image->store('shop_categories/' . $category->id, 'public');
            $category->update(['image_url' => $path]);
        }

        return [
            'success' => true,
            'message' => 'Shop category updated successfully.',
            'category' => $this->formatCategory($category->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $category = Category::find($id);
        if (!$category) {
            return ['success' => false, 'message' => 'Shop category not found.', 'status_code' => 404];
        }

        $category->delete();

        return ['success' => true, 'message' => 'Shop category deleted successfully.', 'status_code' => 200];
    }

    private function formatCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'image_url' => $category->image_full_url,
            'parent_id' => $category->parent_id,
            'is_active' => $category->is_active,
            'sort_order' => $category->sort_order,
            'created_at' => $category->created_at?->toIso8601String(),
            'updated_at' => $category->updated_at?->toIso8601String(),
        ];
    }
}
