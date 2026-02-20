<?php

namespace App\Http\Requests\Api\Stripe;

use App\Http\Requests\Api\ApiFormRequest;

class GetMemberStripeTokenRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'number' => 'required|string',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'cvc' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'Card number is required',
            'exp_month.required' => 'Expiry month is required',
            'exp_year.required' => 'Expiry year is required',
            'cvc.required' => 'CVC is required',
        ];
    }
}
