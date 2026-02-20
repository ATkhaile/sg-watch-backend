<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class RequestEmailChangeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'new_email.required' => '新しいメールアドレスを入力してください',
            'new_email.email' => '有効なメールアドレスを入力してください',
            'new_email.max' => 'メールアドレスは255文字以内で入力してください',
            'new_email.unique' => 'このメールアドレスは既に使用されています',
            'password.required' => 'パスワードを入力してください',
        ];
    }
}
