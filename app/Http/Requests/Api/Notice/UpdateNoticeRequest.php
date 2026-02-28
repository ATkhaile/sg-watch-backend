<?php

namespace App\Http\Requests\Api\Notice;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateNoticeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
