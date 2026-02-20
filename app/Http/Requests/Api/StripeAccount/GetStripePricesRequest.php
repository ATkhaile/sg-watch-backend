<?php

namespace App\Http\Requests\Api\StripeAccount;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Support\Str;

class GetStripePricesRequest extends ApiFormRequest
{
    protected function prepareForValidation()
    {
        if ($this->has('active')) {
            $value = $this->input('active');
            if (Str::lower($value) === 'true') {
                $this->merge(['active' => 1]);
            } elseif (Str::lower($value) === 'false') {
                $this->merge(['active' => 0]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'product_id' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
            'starting_after' => 'nullable|string',
            'ending_before' => 'nullable|string',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.string' => __('stripe_account.validation.product_id.string'),
            'limit.integer' => __('stripe_account.validation.limit.integer'),
            'limit.min' => __('stripe_account.validation.limit.min'),
            'limit.max' => __('stripe_account.validation.limit.max'),
            'starting_after.string' => __('stripe_account.validation.starting_after.string'),
            'ending_before.string' => __('stripe_account.validation.ending_before.string'),
            'active.boolean' => __('stripe_account.validation.active.boolean'),
        ];
    }
}
