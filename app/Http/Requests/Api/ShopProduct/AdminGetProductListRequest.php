<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Enums\StockType;
use App\Http\Requests\Api\ApiFormRequest;

class AdminGetProductListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isSortByDisplayOrder = $this->input('sort_by') === 'display_order';

        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,unisex,couple'],
            'category_id' => [$isSortByDisplayOrder ? 'required_without:brand_id' : 'nullable', 'integer', 'exists:shop_categories,id'],
            'movement_type' => ['nullable', 'string', 'in:quartz,automatic,manual,solar,kinetic'],
            'is_active' => ['nullable', 'boolean'],
            'is_domestic' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'in_stock' => ['nullable', 'boolean'],
            'stock_type' => ['nullable', 'string', 'in:' . implode(',', StockType::getValues())],
            'sort_by' => ['nullable', 'string', 'in:newest,display_order,price_asc,price_desc,name_asc,name_desc'],
            'brand_id' => [$isSortByDisplayOrder ? 'required_without:category_id' : 'nullable', 'integer', 'exists:shop_brands,id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
