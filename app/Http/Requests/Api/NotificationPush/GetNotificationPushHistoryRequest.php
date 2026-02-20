<?php

namespace App\Http\Requests\Api\NotificationPush;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\NotificationPush;
use Illuminate\Validation\Rule;

class GetNotificationPushHistoryRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge([
            'id' => $this->route('notification_push_id'),
        ]);

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists(NotificationPush::class, 'id')->whereNull('deleted_at'),
            ],
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('notification_push.validation.id.required'),
            'id.integer' => __('notification_push.validation.id.integer'),
            'id.exists' => __('notification_push.validation.id.exists'),
            'page.integer' => __('notification_push.validation.page.integer'),
            'page.min' => __('notification_push.validation.page.min'),
            'limit.integer' => __('notification_push.validation.limit.integer'),
            'limit.min' => __('notification_push.validation.limit.min'),
        ];
    }
}
