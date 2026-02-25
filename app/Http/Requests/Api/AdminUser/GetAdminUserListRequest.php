<?php

namespace App\Http\Requests\Api\AdminUser;

use App\Http\Requests\Api\ApiFormRequest;

class GetAdminUserListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other,unknown'],
            'is_system_admin' => ['nullable', 'boolean'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
            'sort_by' => ['nullable', 'string', 'in:created_at_asc,created_at_desc,name_asc,name_desc,email_asc,email_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
