<?php

namespace App\Services;

use App\Repositories\ExternalCostRepository;
use App\Models\ExternalCost;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

class ExternalCostService
{
    public function __construct(
        private ExternalCostRepository $externalCostRepository
    ) {}

    /**
     * 外注費一覧を取得
     */
    public function getAllExternalCosts(
        ?int $projectId = null,
        ?string $category = null,
        ?bool $paidOnly = null,
        string $sortBy = 'payment_due_date',
        string $sortDirection = 'asc',
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->externalCostRepository->findAll(
            projectId: $projectId,
            category: $category,
            paidOnly: $paidOnly,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            perPage: $perPage
        );
    }

    /**
     * 外注費を作成
     */
    public function createExternalCost(array $data): array
    {
        // プロジェクトの存在確認
        $project = Project::find($data['project_id']);
        if (!$project) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        $externalCost = $this->externalCostRepository->create($data);

        return [
            'success' => true,
            'message' => '外注費を登録しました。',
            'data' => $externalCost->load(['project']),
        ];
    }

    /**
     * 外注費を更新
     */
    public function updateExternalCost(int $id, array $data): array
    {
        $externalCost = $this->externalCostRepository->findById($id);

        if (!$externalCost) {
            return [
                'success' => false,
                'message' => '外注費が見つかりません。',
            ];
        }

        $this->externalCostRepository->update($id, $data);
        $updatedExternalCost = $this->externalCostRepository->findById($id);

        return [
            'success' => true,
            'message' => '外注費を更新しました。',
            'data' => $updatedExternalCost,
        ];
    }

    /**
     * 外注費を削除
     */
    public function deleteExternalCost(int $id): array
    {
        $externalCost = $this->externalCostRepository->findById($id);

        if (!$externalCost) {
            return [
                'success' => false,
                'message' => '外注費が見つかりません。',
            ];
        }

        $this->externalCostRepository->delete($id);

        return [
            'success' => true,
            'message' => '外注費を削除しました。',
        ];
    }

    /**
     * 外注費を支払い済みにマーク
     */
    public function markAsPaid(int $id): array
    {
        $externalCost = $this->externalCostRepository->findById($id);

        if (!$externalCost) {
            return [
                'success' => false,
                'message' => '外注費が見つかりません。',
            ];
        }

        $this->externalCostRepository->markAsPaid($id);

        return [
            'success' => true,
            'message' => '外注費を支払い済みにしました。',
        ];
    }

    /**
     * 税込金額を計算
     */
    public function calculateTaxIncluded(ExternalCost $externalCost): float
    {
        return $externalCost->getTaxIncludedAmount();
    }
}
