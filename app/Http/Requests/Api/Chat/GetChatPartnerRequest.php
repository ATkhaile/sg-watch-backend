<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class GetChatPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // partner_id is taken from route parameter
        ];
    }

    public function getPartnerId(): int
    {
        return (int) $this->route('partnerId');
    }
}
