<?php

namespace App\Domain\ShopInventoryHistory\Infrastructure;

use App\Domain\ShopInventoryHistory\Repository\ShopInventoryHistoryRepository;
use App\Models\Shop\InventoryHistory;

class DbShopInventoryHistoryInfrastructure implements ShopInventoryHistoryRepository
{
    public function getList(array $filters): array
    {
        $query = InventoryHistory::query()
            ->with(['product:id,name,sku,primary_image', 'productColor:id,color_name,color_code,sku']);

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        } else {
            if (!empty($filters['start_date'])) {
                $query->whereDate('created_at', '>=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $query->whereDate('created_at', '<=', $filters['end_date']);
            }
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['product_color_id'])) {
            $query->where('product_color_id', $filters['product_color_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        $perPage = $filters['per_page'] ?? 20;
        $page = $filters['page'] ?? 1;

        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

        $records = collect($paginator->items())->map(fn ($h) => $this->formatHistory($h))->values()->all();

        return [
            'records' => $records,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];
    }

    private function formatHistory(InventoryHistory $history): array
    {
        return [
            'id'               => $history->id,
            'type'             => $history->type,
            'quantity'         => $history->quantity,
            'stock_before'     => $history->stock_before,
            'stock_after'      => $history->stock_after,
            'reference_type'   => $history->reference_type,
            'reference_id'     => $history->reference_id,
            'note'             => $history->note,
            'created_at'       => $history->created_at?->toDateTimeString(),
            'product'          => $history->product ? [
                'id'               => $history->product->id,
                'name'             => $history->product->name,
                'sku'              => $history->product->sku,
                'primary_image_url' => $history->product->primary_image_url,
            ] : null,
            'product_color'    => $history->productColor ? [
                'id'         => $history->productColor->id,
                'color_name' => $history->productColor->color_name,
                'color_code' => $history->productColor->color_code,
                'sku'        => $history->productColor->sku,
            ] : null,
        ];
    }
}
