<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ForgotPasswordRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'redirect_url' => 'nullable|url|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');

            $emailExists = User::where('email', $email)
                ->whereNull('deleted_at')
                ->exists();

            try {
                if (!$emailExists && Schema::hasTable('email_addresses')) {
                    $emailAddressClass = 'App\Models\EmailAddress';
                    if (class_exists($emailAddressClass)) {
                        $emailAddressExists = $emailAddressClass::where('email', $email)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($emailAddressExists) {
                            return;
                        }
                    }
                } elseif ($emailExists) {
                    return;
                }
            } catch (\Throwable $th) {
                Log::error('Email validation error:', ['error' => $th->getMessage()]);
            }

            $validator->errors()->add('email', __('auth.validation.email.notfound'));
        });
    }

    public function messages(): array
    {
        return [
            'email.required' => __('auth.validation.email.required'),
            'email.email' => __('auth.validation.email.email'),
        ];
    }
}
