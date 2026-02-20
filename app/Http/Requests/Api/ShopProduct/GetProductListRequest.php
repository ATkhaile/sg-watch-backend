<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Http\Requests\Api\ApiFormRequest;

class GetProductListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,unisex'],
            'brand_id' => ['nullable', 'integer', 'exists:shop_brands,id'],
            'movement_type' => ['nullable', 'string', 'in:quartz,automatic,manual,solar,kinetic'],
            'in_stock' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'sort_by' => ['nullable', 'string', 'in:newest,price_asc,price_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
