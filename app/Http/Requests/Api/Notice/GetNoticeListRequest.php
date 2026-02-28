<?php

namespace App\Http\Requests\Api\Notice;

use App\Http\Requests\Api\ApiFormRequest;

class GetNoticeListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
