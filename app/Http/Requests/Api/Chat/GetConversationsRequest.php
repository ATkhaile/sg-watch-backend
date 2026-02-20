<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class GetConversationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:50',
            'search' => 'nullable|string|max:255',
            'message_search' => 'nullable|string|max:255',
        ];
    }

    public function getPage(): int
    {
        return $this->input('page', 1);
    }

    public function getLimit(): int
    {
        return $this->input('limit', 10);
    }

    public function getSearch(): ?string
    {
        return $this->input('search');
    }

    public function getMessageSearch(): ?string
    {
        return $this->input('message_search');
    }
}
