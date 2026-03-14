<?php

namespace App\Http\Requests\Api\BigSale;

use App\Http\Requests\Api\ApiFormRequest;

class GetBigSaleListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => ['nullable', 'boolean'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', 'string', 'in:created_at_asc,created_at_desc,sale_start_date_asc,sale_start_date_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
