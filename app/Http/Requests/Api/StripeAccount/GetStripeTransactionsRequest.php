<?php

namespace App\Http\Requests\Api\StripeAccount;

use App\Http\Requests\Api\ApiFormRequest;

class GetStripeTransactionsRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer|min:1|max:100',
            'starting_after' => 'nullable|string',
            'ending_before' => 'nullable|string',
            'created' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'limit.integer' => __('stripe_account.validation.limit.integer'),
            'limit.min' => __('stripe_account.validation.limit.min'),
            'limit.max' => __('stripe_account.validation.limit.max'),
            'starting_after.string' => __('stripe_account.validation.starting_after.string'),
            'ending_before.string' => __('stripe_account.validation.ending_before.string'),
            'created.integer' => __('stripe_account.validation.created.integer'),
        ];
    }
}
