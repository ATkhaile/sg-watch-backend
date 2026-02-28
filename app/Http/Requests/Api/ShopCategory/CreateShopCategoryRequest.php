<?php

namespace App\Http\Requests\Api\ShopCategory;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateShopCategoryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('shop_categories', 'slug')->whereNull('deleted_at')],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
