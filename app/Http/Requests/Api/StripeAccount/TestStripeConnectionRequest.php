<?php

namespace App\Http\Requests\Api\StripeAccount;

use App\Http\Requests\Api\ApiFormRequest;

class TestStripeConnectionRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'public_key' => 'required|string|starts_with:pk_',
            'secret_key' => 'required|string|starts_with:sk_',
            'webhook_secret' => 'nullable|string|starts_with:whsec_',
        ];
    }

    public function messages(): array
    {
        return [
            'public_key.required' => '公開可能キーは必須です',
            'public_key.string' => '公開可能キーは文字列で入力してください',
            'public_key.starts_with' => '公開可能キーは pk_ で始まる必要があります',
            'secret_key.required' => '秘密キーは必須です',
            'secret_key.string' => '秘密キーは文字列で入力してください',
            'secret_key.starts_with' => '秘密キーは sk_ で始まる必要があります',
            'webhook_secret.string' => 'Webhook署名シークレットは文字列で入力してください',
            'webhook_secret.starts_with' => 'Webhook署名シークレットは whsec_ で始まる必要があります',
        ];
    }
}
