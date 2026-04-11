<?php

namespace App\Domain\Post\Infrastructure;

use App\Components\CommonComponent;
use App\Domain\Post\Repository\PostRepository;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DbPostInfrastructure implements PostRepository
{
    public function getList(array $filters): array
    {
        $query = Post::query();

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
            'sort_order_asc' => $query->orderBy('sort_order', 'asc'),
            'sort_order_desc' => $query->orderBy('sort_order', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'posts' => collect($paginator->items())->map(fn($post) => $this->formatPost($post))->toArray(),
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
        $post = Post::find($id);
        if (!$post) {
            return null;
        }
        return $this->formatPost($post);
    }

    public function create(array $data): array
    {
        $media = $data['media'] ?? null;
        unset($data['media']);

        if ($media instanceof UploadedFile) {
            $path = $media->store('posts', 'public');
            $data['media_url'] = $path;
            $data['media_type'] = $this->detectMediaType($media);
        }

        $post = Post::create($data);

        return [
            'success' => true,
            'message' => 'Post created successfully.',
            'post' => $this->formatPost($post),
        ];
    }

    public function update(int $id, array $data): array
    {
        $post = Post::find($id);
        if (!$post) {
            return ['success' => false, 'message' => 'Post not found.'];
        }

        $media = $data['media'] ?? null;
        unset($data['media']);

        $post->update($data);

        if ($media instanceof UploadedFile) {
            if ($post->media_url && Storage::disk('public')->exists($post->media_url)) {
                Storage::disk('public')->delete($post->media_url);
            }
            $path = $media->store('posts/' . $post->id, 'public');
            $post->update([
                'media_url' => $path,
                'media_type' => $this->detectMediaType($media),
            ]);
        }

        return [
            'success' => true,
            'message' => 'Post updated successfully.',
            'post' => $this->formatPost($post->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $post = Post::find($id);
        if (!$post) {
            return ['success' => false, 'message' => 'Post not found.', 'status_code' => 404];
        }

        $post->delete();

        return ['success' => true, 'message' => 'Post deleted successfully.', 'status_code' => 200];
    }

    public function getPublicList(array $filters): array
    {
        $query = Post::where('is_active', true);

        if (isset($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'posts' => collect($paginator->items())->map(fn($post) => $this->formatPost($post))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getPublicDetail(int $id): ?array
    {
        $post = Post::where('is_active', true)->find($id);
        if (!$post) {
            return null;
        }
        return $this->formatPost($post);
    }

    private function formatPost(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'link' => $post->link,
            'media_url' => $post->media_full_url,
            'media_type' => $post->media_type,
            'is_active' => $post->is_active,
            'sort_order' => $post->sort_order,
            'created_at' => $post->created_at?->toIso8601String(),
            'updated_at' => $post->updated_at?->toIso8601String(),
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
