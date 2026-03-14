<?php

namespace App\Http\Requests\Api\BigSale;

use App\Http\Requests\Api\ApiFormRequest;

class CreateBigSaleRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'media' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,webm', 'max:102400'],
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['required', 'integer', 'exists:shop_products,id'],
            'sale_start_date' => ['required', 'date'],
            'sale_end_date' => ['required', 'date', 'after_or_equal:sale_start_date'],
            'sale_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
