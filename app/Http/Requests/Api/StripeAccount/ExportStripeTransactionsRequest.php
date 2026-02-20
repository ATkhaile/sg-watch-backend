<?php

namespace App\Http\Requests\Api\StripeAccount;

use Illuminate\Foundation\Http\FormRequest;

class ExportStripeTransactionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer|min:1|max:10000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }
}
