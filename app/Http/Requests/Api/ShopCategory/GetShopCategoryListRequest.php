<?php

namespace App\Http\Requests\Api\ShopCategory;

use App\Http\Requests\Api\ApiFormRequest;

class GetShopCategoryListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'sort_by' => ['nullable', 'string', 'in:sort_order_asc,sort_order_desc,created_at_asc,created_at_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
