<?php

namespace App\Http\Requests\Api\Notice;

use App\Http\Requests\Api\ApiFormRequest;

class CreateNoticeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
