<?php

namespace App\Http\Requests\Api\ShopInventoryHistory;

use App\Http\Requests\Api\ApiFormRequest;

class GetInventoryHistoryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'       => ['nullable', 'date_format:Y-m-d'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'product_id'       => ['nullable', 'integer', 'exists:shop_products,id'],
            'product_color_id' => ['nullable', 'integer', 'exists:shop_product_colors,id'],
            'type'             => ['nullable', 'string', 'in:import,export'],
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
