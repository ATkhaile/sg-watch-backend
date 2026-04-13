<?php

namespace App\Http\Requests\Api\DiscountCode;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountCodeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'max:255', Rule::unique('discount_codes', 'code')->whereNull('deleted_at')->ignore($this->route('id'))],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'amount' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}
