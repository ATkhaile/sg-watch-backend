<?php
namespace App\Http\Requests\Api\FcmToken;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class GetUserFcmTokensRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;
    public function rules(): array
    {
        $this->merge(['user_id' => $this->route('user_id')]);

        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __('fcm_token.validation.user_id.required'),
            'user_id.integer'  => __('fcm_token.validation.user_id.integer'),
            'user_id.exists'   => __('fcm_token.validation.user_id.exists'),
        ];
    }
}