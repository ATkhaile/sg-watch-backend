<?php

return [
    'get' => [
        'message' => 'User profile retrieved successfully.',
        'failed' => 'Failed to retrieve user profile.',
    ],
    'update' => [
        'success' => 'Profile updated successfully.',
        'failed' => 'Failed to update profile.',
    ],
    'validation' => [
        'not_found' => 'User not found.',
        'email_send' => [
            'required' => 'Email address is required.',
            'email' => 'Please enter a valid email address.',
            'max' => 'Email address must be at most 255 characters.',
        ],
        'postal_code' => [
            'required' => 'Postal code is required.',
            'regex' => 'Invalid postal code format.',
            'max' => 'Postal code must be at most 8 characters.',
        ],
        'prefecture_id' => [
            'required' => 'Prefecture is required.',
            'integer' => 'Prefecture ID must be an integer.',
            'exists' => 'Selected prefecture does not exist.',
        ],
        'city' => [
            'required' => 'City is required.',
            'max' => 'City must be at most 255 characters.',
        ],
        'password' => [
            'min' => 'Password must be at least 8 characters.',
            'max' => 'Password must be at most 16 characters.',
            'regex' => 'Password must contain both letters and numbers.',
        ],
        'street_address' => [
            'required' => 'Street address is required.',
            'max' => 'Street address must be at most 255 characters.',
        ],
        'building' => [
            'max' => 'Building name must be at most 255 characters.',
        ],
        'line_name' => [
            'required' => 'Line name is required.',
            'max' => 'Line name must be at most 255 characters.',
        ],
        'last_name_kanji' => [
            'required' => 'Last name (Kanji) is required.',
            'max' => 'Last name (Kanji) must be at most 255 characters.',
        ],
        'first_name_kanji' => [
            'required' => 'First name (Kanji) is required.',
            'max' => 'First name (Kanji) must be at most 255 characters.',
        ],
        'last_name_kana' => [
            'required' => 'Last name (Kana) is required.',
            'max' => 'Last name (Kana) must be at most 255 characters.',
            'regex' => 'Last name (Kana) format is invalid.',
        ],
        'first_name_kana' => [
            'required' => 'First name (Kana) is required.',
            'max' => 'First name (Kana) must be at most 255 characters.',
            'regex' => 'First name (Kana) format is invalid.',
        ],
        'group_name' => [
            'max' => 'Group name must be at most 255 characters.',
        ],
        'group_name_kana' => [
            'max' => 'Group name (Kana) must be at most 255 characters.',
            'regex' => 'Group name (Kana) format is invalid.',
        ],
        'phone' => [
            'required' => 'Phone number is required.',
            'max' => 'Phone number must be at most 16 characters.',
            'regex' => 'Invalid phone number format.',
        ],
        'is_black' => [
            'required' => 'Black list flag is required.',
            'boolean' => 'Black list flag must be boolean.',
        ],
        'billing_same_address_flag' => [
            'required' => 'Billing same address flag is required.',
            'boolean' => 'Billing same address flag must be boolean.',
        ],
        'memo' => [
            'max' => 'Memo must be at most 1000 characters.',
        ],
        'birthday' => [
            'required' => 'Birthday is required.',
            'date_format' => 'Birthday must be in YYYY/MM/DD format.',
        ],
    ],
];