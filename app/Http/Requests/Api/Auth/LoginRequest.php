<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class LoginRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required_without:user_id',
                'email',
            ],
            'user_id' => [
                'required_without:email',
                'exists:users,user_id',
            ],
            'password' => 'required',
            'verification_code' => 'nullable|string'
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('email', function ($attribute, $value, $fail) {
            $emailExists = User::where('email', $value)->exists();

            try {
                if (Schema::hasTable('email_addresses')) {
                    $emailAddressClass = 'App\Models\EmailAddress';
                    if (class_exists($emailAddressClass)) {
                        $emailAddressExists = $emailAddressClass::where('email', $value)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($emailExists || $emailAddressExists) {
                            return;
                        }
                    }
                }
            } catch (\Throwable $th) {

            }

            $fail(__('auth.validation.email.notfound'));
        }, function ($input) {
            return !empty($input->email);
        });

        $validator->sometimes('user_id', 'exists:users,user_id', function ($input) {
            return !empty($input->user_id);
        });
    }

    public function messages(): array
    {
        return [
            'email.required_without' => __('auth.validation.email.required_without'),
            'email.email' => __('auth.validation.email.email'),
            'user_id.required_without' => __('auth.validation.user_id.required_without'),
            'user_id.exists' => __('auth.validation.user_id.exists'),
            'password.required' => __('auth.validation.password.required'),
        ];
    }
}
