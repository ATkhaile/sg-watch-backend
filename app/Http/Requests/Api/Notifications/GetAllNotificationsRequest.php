<?php

namespace App\Http\Requests\Api\Notifications;

use App\Enums\PushType;
use App\Enums\SenderType;
use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class GetAllNotificationsRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255',
            'type' => [
                'nullable',
                'string',
                'max:50',
                Rule::in(PushType::getValues()),
            ],
            'sort' => [
                'nullable',
                'string',
                Rule::in(Schema::getColumnListing('notifications')),
            ],
            'direction' => 'nullable|in:ASC,DESC,asc,desc',
            'sender_type' => [
                'nullable',
                'string',
                'max:50',
                Rule::in(SenderType::getValues()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => __('notifications.validation.page.integer'),
            'page.min' => __('notifications.validation.page.min'),
            'limit.integer' => __('notifications.validation.per_page.integer'),
            'limit.min' => __('notifications.validation.per_page.min'),
            'search.string' => __('notifications.validation.search.string'),
            'search.max' => __('notifications.validation.search.max'),
            'type.string' => __('notifications.validation.type.string'),
            'type.max' => __('notifications.validation.type.max'),
            'type.in' => __('notifications.validation.type.in'),
            'sort.string' => __('notifications.validation.sort.string'),
            'sort.in' => __('notifications.validation.sort.in'),
            'direction.in' => __('notifications.validation.direction.in'),
            'sender_type.string' => __('notifications.validation.sender_type.string'),
            'sender_type.max' => __('notifications.validation.sender_type.max'),
            'sender_type.in' => __('notifications.validation.sender_type.in'),
        ];
    }
}
