<?php

namespace App\Domain\DiscountCode\Infrastructure;

use App\Domain\DiscountCode\Repository\DiscountCodeRepository;
use App\Models\DiscountCode;

class DbDiscountCodeInfrastructure implements DiscountCodeRepository
{
    public function getList(array $filters): array
    {
        $query = DiscountCode::query();

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['keyword'])) {
            $query->where('code', 'like', '%' . $filters['keyword'] . '%');
        }

        $sortBy = $filters['sort_by'] ?? 'created_at_desc';
        match ($sortBy) {
            'created_at_asc' => $query->orderBy('created_at', 'asc'),
            'code_asc' => $query->orderBy('code', 'asc'),
            'code_desc' => $query->orderBy('code', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'discount_codes' => collect($paginator->items())->map(fn($item) => $this->formatDiscountCode($item))->toArray(),
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
        $discountCode = DiscountCode::find($id);
        if (!$discountCode) {
            return null;
        }
        return $this->formatDiscountCode($discountCode);
    }

    public function create(array $data): array
    {
        $discountCode = DiscountCode::create($data);

        return [
            'success' => true,
            'message' => 'Discount code created successfully.',
            'discount_code' => $this->formatDiscountCode($discountCode),
        ];
    }

    public function update(int $id, array $data): array
    {
        $discountCode = DiscountCode::find($id);
        if (!$discountCode) {
            return ['success' => false, 'message' => 'Discount code not found.'];
        }

        $discountCode->update($data);

        return [
            'success' => true,
            'message' => 'Discount code updated successfully.',
            'discount_code' => $this->formatDiscountCode($discountCode->fresh()),
        ];
    }

    public function delete(int $id): array
    {
        $discountCode = DiscountCode::find($id);
        if (!$discountCode) {
            return ['success' => false, 'message' => 'Discount code not found.', 'status_code' => 404];
        }

        $discountCode->delete();

        return ['success' => true, 'message' => 'Discount code deleted successfully.', 'status_code' => 200];
    }

    public function getByCode(string $code): ?array
    {
        $discountCode = DiscountCode::where('code', $code)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->first();

        if (!$discountCode) {
            return null;
        }

        return [
            'code' => $discountCode->code,
            'percentage' => $discountCode->percentage,
            'available' => true,
        ];
    }

    private function formatDiscountCode(DiscountCode $discountCode): array
    {
        return [
            'id' => $discountCode->id,
            'code' => $discountCode->code,
            'quantity' => $discountCode->quantity,
            'percentage' => $discountCode->percentage,
            'is_active' => $discountCode->is_active,
            'created_at' => $discountCode->created_at?->toIso8601String(),
            'updated_at' => $discountCode->updated_at?->toIso8601String(),
        ];
    }
}
