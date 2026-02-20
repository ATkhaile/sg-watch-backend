<?php

namespace App\Repositories;

use App\Models\ExternalCost;
use Illuminate\Pagination\LengthAwarePaginator;

class ExternalCostRepository
{
    /**
     * 外注費一覧を取得（ページネーション付き）
     */
    public function findAll(
        ?int $projectId = null,
        ?string $category = null,
        ?bool $paidOnly = null,
        string $sortBy = 'payment_due_date',
        string $sortDirection = 'asc',
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = ExternalCost::with(['project']);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($paidOnly !== null) {
            if ($paidOnly) {
                $query->whereNotNull('paid_at');
            } else {
                $query->whereNull('paid_at');
            }
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * 外注費を作成
     */
    public function create(array $data): ExternalCost
    {
        return ExternalCost::create($data);
    }

    /**
     * 外注費を更新
     */
    public function update(int $id, array $data): bool
    {
        $externalCost = ExternalCost::find($id);
        if (!$externalCost) {
            return false;
        }

        return $externalCost->update($data);
    }

    /**
     * 外注費を削除（ソフトデリート）
     */
    public function delete(int $id): bool
    {
        $externalCost = ExternalCost::find($id);
        if (!$externalCost) {
            return false;
        }

        return $externalCost->delete();
    }

    /**
     * IDで外注費を取得
     */
    public function findById(int $id): ?ExternalCost
    {
        return ExternalCost::with(['project'])->find($id);
    }

    /**
     * プロジェクトIDで外注費を取得
     */
    public function findByProjectId(int $projectId)
    {
        return ExternalCost::where('project_id', $projectId)->get();
    }

    /**
     * 支払い済みにマーク
     */
    public function markAsPaid(int $id): bool
    {
        return $this->update($id, ['paid_at' => now()]);
    }
}
