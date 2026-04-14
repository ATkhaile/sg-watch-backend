<?php

namespace App\Domain\ShopReview\Infrastructure;

use App\Domain\ShopReview\Repository\ShopReviewRepository;
use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\Review;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DbShopReviewInfrastructure implements ShopReviewRepository
{
    public function create(int $userId, array $data): array
    {
        $productId = $data['product_id'];

        // Check if user has a completed/delivered order containing this product
        $hasPurchased = Order::where('user_id', $userId)
            ->whereIn('status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
            ->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        if (!$hasPurchased) {
            return ['success' => false, 'message' => 'You can only review products you have purchased.'];
        }

        // Check if already reviewed (include soft-deleted)
        $existing = Review::withTrashed()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing && !$existing->trashed()) {
            return ['success' => false, 'message' => 'You have already reviewed this product.'];
        }

        $imageUrls = $this->storeImages($data['images'] ?? [], $userId);

        if ($existing && $existing->trashed()) {
            $existing->restore();
            $existing->update([
                'rating' => $data['rating'],
                'title' => $data['title'] ?? null,
                'comment' => $data['comment'] ?? null,
                'image_urls' => $imageUrls ?: null,
                'is_approved' => true,
            ]);
            $review = $existing;
        } else {
            $review = Review::create([
                'product_id' => $productId,
                'user_id' => $userId,
                'rating' => $data['rating'],
                'title' => $data['title'] ?? null,
                'comment' => $data['comment'] ?? null,
                'image_urls' => $imageUrls ?: null,
                'is_approved' => true,
            ]);
        }

        // Update product rating stats
        $review->product->updateRatingStats();

        $review->load('user:id,first_name,last_name');

        return [
            'success' => true,
            'message' => 'Review submitted successfully.',
            'review' => $this->formatReview($review, $userId),
        ];
    }

    public function update(int $userId, int $reviewId, array $data): array
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', $userId)
            ->first();

        if (!$review) {
            return ['success' => false, 'message' => 'Review not found.'];
        }

        $updateData = [];

        if (isset($data['rating'])) {
            $updateData['rating'] = $data['rating'];
        }
        if (array_key_exists('title', $data)) {
            $updateData['title'] = $data['title'];
        }
        if (array_key_exists('comment', $data)) {
            $updateData['comment'] = $data['comment'];
        }
        if (array_key_exists('existing_images', $data) || array_key_exists('images', $data)) {
            $oldImages = $review->image_urls ?? [];
            $keepImages = $data['existing_images'] ?? [];

            // Delete removed images from storage
            $removedImages = array_diff($oldImages, $keepImages);
            $this->deleteStoredImages($removedImages);

            // Upload new images
            $newImages = $this->storeImages($data['images'] ?? [], $review->user_id);

            $allImages = array_merge($keepImages, $newImages);
            $updateData['image_urls'] = $allImages ?: null;
        }

        $review->update($updateData);

        $review->product->updateRatingStats();

        $review->load('user:id,first_name,last_name');

        return [
            'success' => true,
            'message' => 'Review updated successfully.',
            'review' => $this->formatReview($review, $userId),
        ];
    }

    public function delete(int $userId, int $reviewId): array
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', $userId)
            ->first();

        if (!$review) {
            return ['success' => false, 'message' => 'Review not found.'];
        }

        $this->deleteStoredImages($review->image_urls ?? []);
        $product = $review->product;
        $review->delete();
        $product->updateRatingStats();

        return [
            'success' => true,
            'message' => 'Review deleted successfully.',
        ];
    }

    public function getById(int $reviewId, ?int $authUserId = null): ?array
    {
        $review = Review::where('id', $reviewId)
            ->with(['user:id,first_name,last_name', 'product:id,name,slug'])
            ->first();

        if (!$review) {
            return null;
        }

        $data = $this->formatReview($review, $authUserId);
        $data['product'] = $review->product ? [
            'id' => $review->product->id,
            'name' => $review->product->name,
            'slug' => $review->product->slug,
        ] : null;

        return $data;
    }

    public function getByProduct(int $productId, int $perPage, ?int $authUserId = null): array
    {
        $paginator = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->with('user:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return [
            'reviews' => collect($paginator->items())->map(fn (Review $r) => $this->formatReview($r, $authUserId))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getMyReviews(int $userId, int $perPage): array
    {
        $paginator = Review::where('user_id', $userId)
            ->with(['user:id,first_name,last_name', 'product:id,name,slug'])
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return [
            'reviews' => collect($paginator->items())->map(function (Review $r) use ($userId) {
                $data = $this->formatReview($r, $userId);
                $data['product'] = $r->product ? [
                    'id' => $r->product->id,
                    'name' => $r->product->name,
                    'slug' => $r->product->slug,
                ] : null;
                return $data;
            })->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    private function storeImages(array $images, int $userId): array
    {
        $urls = [];
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $urls[] = $image->store('reviews/' . $userId, 'public');
            }
        }
        return $urls;
    }

    private function deleteStoredImages(array $imageUrls): void
    {
        foreach ($imageUrls as $url) {
            Storage::disk('public')->delete($url);
        }
    }

    private function formatReview(Review $review, ?int $authUserId = null): array
    {
        return [
            'id' => $review->id,
            'product_id' => $review->product_id,
            'rating' => $review->rating,
            'title' => $review->title,
            'comment' => $review->comment,
            'image_base_url' => rtrim(Storage::disk('public')->url(''), '/') . '/',
            'image_urls' => $review->image_urls,
            'is_approved' => $review->is_approved,
            'is_owner' => $authUserId !== null && $authUserId === $review->user_id,
            'user' => $review->user ? [
                'first_name' => $review->user->first_name,
                'last_name' => $review->user->last_name,
            ] : null,
            'created_at' => $review->created_at?->toIso8601String(),
            'updated_at' => $review->updated_at?->toIso8601String(),
        ];
    }
}
