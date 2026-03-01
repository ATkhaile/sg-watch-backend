<?php

namespace App\Http\Requests\Api\DiscountCode;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateDiscountCodeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('discount_codes', 'code')->whereNull('deleted_at')],
            'quantity' => ['required', 'integer', 'min:0'],
            'percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}
