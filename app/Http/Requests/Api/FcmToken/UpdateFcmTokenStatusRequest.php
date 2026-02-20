<?php

namespace App\Http\Requests\Api\FcmToken;

use App\Enums\ActiveStatus;
use App\Http\Requests\Api\ApiFormRequest;
use App\Models\FcmToken;
use Illuminate\Validation\Rule;

class UpdateFcmTokenStatusRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;
    public function rules(): array
    {
        $this->merge([
            'fcm_token_id' => $this->route('fcm_token_id'),
        ]);

        return [
            'fcm_token_id' => [
                'required',
                'integer',
                Rule::exists(FcmToken::class, 'id')->whereNull('deleted_at'),
            ],
            'active_status' => [
                'required',
                'string',
                Rule::in(ActiveStatus::getValues()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token_id.required' => __('fcm_token.validation.fcm_token_id.required'),
            'fcm_token_id.integer'  => __('fcm_token.validation.fcm_token_id.integer'),
            'fcm_token_id.exists'   => __('fcm_token.validation.fcm_token_id.exists'),

            'active_status.required' => __('fcm_token.validation.active_status.required'),
            'active_status.string'   => __('fcm_token.validation.active_status.string'),
            'active_status.in'       => __('fcm_token.validation.active_status.in'),
        ];
    }
}
