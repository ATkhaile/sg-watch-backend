<?php

namespace App\Http\Requests\Api\BigSale;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateBigSaleRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'media' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,webm', 'max:102400'],
            'product_ids' => ['nullable', 'array', 'min:1'],
            'product_ids.*' => ['required', 'integer', 'exists:shop_products,id'],
            'sale_start_date' => ['nullable', 'date'],
            'sale_end_date' => ['nullable', 'date', 'after_or_equal:sale_start_date'],
            'sale_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
