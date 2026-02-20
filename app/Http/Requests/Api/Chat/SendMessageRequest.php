<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'receiver_id' => 'required|integer|exists:users,id',
            'reply_to_message_id' => 'nullable|integer|exists:chat_messages,id',
            'mentioned_user_ids' => 'nullable|array',
            'mentioned_user_ids.*' => 'integer|exists:users,id',
        ];

        // Message is required if no file, file is optional
        // Max 10,000 characters (always applies when message is provided)
        $rules['message'] = ['required_without:file', 'nullable', 'string', 'max:10000'];
        
        // File validation (optional)
        if ($this->hasFile('file')) {
            $blacklistedExtensions = config('chat_file_restrictions.blacklisted_extensions', []);
            $maxFileSize = config('chat_file_restrictions.max_file_size', 102400);
            $allowedMimeTypes = config('chat_file_restrictions.allowed_mime_types', []);

            $rules['file'] = [
                'nullable',
                'file',
                'max:' . $maxFileSize,
                function ($attribute, $value, $fail) use ($blacklistedExtensions, $allowedMimeTypes) {
                    if (!$value) return; // Skip validation if no file
                    
                    $extension = strtolower($value->getClientOriginalExtension());
                    $mimeType = $value->getMimeType();

                    // Check blacklisted extensions
                    if (in_array($extension, $blacklistedExtensions)) {
                        $fail(__('chat.validation.file.extension_not_allowed'));
                        return;
                    }

                    // Check allowed MIME types
                    if (!empty($allowedMimeTypes) && !in_array($mimeType, $allowedMimeTypes)) {
                        $fail(__('chat.validation.file.mime_type_not_allowed'));
                        return;
                    }
                },
            ];
        } else {
            $rules['file'] = 'nullable|file';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'receiver_id' => __('chat.validation.receiver_id.required'),
            'receiver_id.integer' => __('chat.validation.receiver_id.integer'),
            'message' => __('chat.validation.message.required'),
            'message.string' => __('chat.validation.message.string'),
            'message.max' => __('chat.validation.message.max', ['max' => 10000]),
            'file.required' => __('chat.validation.file.required'),
            'file.file' => __('chat.validation.file.invalid'),
            'file.max' => __('chat.validation.file.too_large'),
        ];
    }

}
