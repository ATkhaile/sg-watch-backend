<?php

namespace App\Http\Requests\Api\Banner;

use App\Http\Requests\Api\ApiFormRequest;

class GetBannerListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', 'string', 'in:sort_order_asc,sort_order_desc,created_at_asc,created_at_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
