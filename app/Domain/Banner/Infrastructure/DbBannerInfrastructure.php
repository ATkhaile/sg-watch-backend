<?php

namespace App\Domain\Banner\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\Banner\Repository\BannerRepository;
use App\Models\Banner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DbBannerInfrastructure implements BannerRepository
{
    public function getList(array $filters): array
    {
        $query = Banner::query();

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
            'banners' => collect($paginator->items())->map(fn($banner) => $this->formatBanner($banner))->toArray(),
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
        $banner = Banner::find($id);
        if (!$banner) {
            return null;
        }
        return $this->formatBanner($banner);
    }

    public function create(array $data): array
    {
        $media = $data['media'] ?? null;
        unset($data['media']);

        if ($media instanceof UploadedFile) {
            $path = $media->store('banners', 'public');
            $data['media_url'] = $path;
            $data['media_type'] = $this->detectMediaType($media);
        }

        $banner = Banner::create($data);

        return [
            'success' => true,
            'message' => 'Banner created successfully.',
            'banner' => $this->formatBanner($banner),
        ];
    }

    public function update(int $id, array $data): array
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return ['success' => false, 'message' => 'Banner not found.'];
        }

        $media = $data['media'] ?? null;
        unset($data['media']);

        $banner->update($data);

        if ($media instanceof UploadedFile) {
            if ($banner->media_url && Storage::disk('public')->exists($banner->media_url)) {
                Storage::disk('public')->delete($banner->media_url);
            }
            $path = $media->store('banners/' . $banner->id, 'public');
            $banner->update([
                'media_url' => $path,
                'media_type' => $this->detectMediaType($media),
            ]);
        }

        return [
            'success' => true,
            'message' => 'Banner updated successfully.',
            'banner' => $this->formatBanner($banner->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return ['success' => false, 'message' => 'Banner not found.', 'status_code' => 404];
        }

        $banner->delete();

        return ['success' => true, 'message' => 'Banner deleted successfully.', 'status_code' => 200];
    }

    public function getPublicList(): array
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return [
            'banners' => $banners->map(fn($banner) => $this->formatBanner($banner))->toArray(),
        ];
    }

    private function formatBanner(Banner $banner): array
    {
        return [
            'id' => $banner->id,
            'media_url' => $banner->media_full_url,
            'media_type' => $banner->media_type,
            'link' => $banner->link,
            'sort_order' => $banner->sort_order,
            'is_active' => $banner->is_active,
            'created_at' => $banner->created_at?->toIso8601String(),
            'updated_at' => $banner->updated_at?->toIso8601String(),
        ];
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
