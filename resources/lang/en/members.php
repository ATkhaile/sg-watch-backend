<?php

return [
    'list' => [
        'message' => 'Members list retrieved successfully',
    ],
    'detail' => [
        'message' => 'Member detail retrieved successfully',
    ],
    'update' => [
        'success' => 'Member updated successfully',
        'failed' => 'Failed to update member',
    ],
    'update_plan' => [
        'success' => 'Payment status of the plan has been updated.',
        'failed'  => 'Failed to update the plan.',
    ],
    'reservations' => [
        'list' => [
            'success' => 'Reservations retrieved successfully',
        ],
    ],
    'reservations_used' => [
        'list' => [
            'success' => 'Used reservations retrieved successfully',
        ],
    ],
    'plan' => [
        'detail' => [
            'success' => 'Plan retrieved successfully',
        ],
    ],
    'validation' => [
        'id' => [
            'required' => 'Member ID is required',
            'integer' => 'Member ID must be an integer',
            'exists' => 'Member does not exist',
        ],
        'is_black' => [
            'required' => 'Black flag is required',
            'boolean' => 'Black flag must be boolean',
        ],
        'last_name_kanji' => [
            'required' => 'Last name kanji is required',
            'max' => 'Last name kanji cannot exceed 255 characters',
        ],
        'first_name_kanji' => [
            'required' => 'First name kanji is required',
            'max' => 'First name kanji cannot exceed 255 characters',
        ],
        'last_name_kana' => [
            'required' => 'Last name kana is required',
            'max' => 'Last name kana cannot exceed 255 characters',
            'regex' => 'Last name kana must be in katakana',
        ],
        'first_name_kana' => [
            'required' => 'First name kana is required',
            'max' => 'First name kana cannot exceed 255 characters',
            'regex' => 'First name kana must be in katakana',
        ],
        'group_name' => [
            'max' => 'Group name cannot exceed 255 characters',
        ],
        'group_name_kana' => [
            'max' => 'Group name kana cannot exceed 255 characters',
            'regex' => 'Group name kana must be in katakana',
        ],
        'email_send' => [
            'required' => 'Email send is required',
            'max' => 'Email send cannot exceed 255 characters',
            'email' => 'Email send must be a valid email address',
        ],
        'birthday' => [
            'required' => 'Birthday is required',
            'date_format' => 'Birthday must be in Y/m/d format',
        ],
        'postal_code' => [
            'required' => 'Postal code is required',
            'max' => 'Postal code cannot exceed 8 characters',
            'regex' => 'Postal code format is invalid',
        ],
        'prefecture_id' => [
            'required' => 'Prefecture ID is required',
            'integer' => 'Prefecture ID must be an integer',
            'exists' => 'Prefecture does not exist',
        ],
        'city' => [
            'required' => 'City is required',
            'max' => 'City cannot exceed 255 characters',
        ],
        'street_address' => [
            'required' => 'Street address is required',
            'max' => 'Street address cannot exceed 255 characters',
        ],
        'building' => [
            'max' => 'Building cannot exceed 255 characters',
        ],
        'phone' => [
            'required' => 'Phone is required',
            'max' => 'Phone cannot exceed 16 characters',
            'regex' => 'Phone format is invalid',
        ],
        'billing_same_address_flag' => [
            'required' => 'Billing same address flag is required',
            'boolean' => 'Billing same address flag must be boolean',
        ],
        'memo' => [
            'max' => 'Memo cannot exceed 1000 characters',
        ],
        'password' => [
            'min' => 'Password must be at least 8 characters',
            'max' => 'Password cannot exceed 16 characters',
            'regex' => 'Password must contain letters and numbers',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
        ],
        'search' => [
            'string' => 'Search must be a string',
            'max' => 'Search cannot exceed 255 characters',
        ],
        'member_id' => [
            'required' => 'Member ID is required.',
            'integer' => 'Please enter a numeric member ID.',
            'exists' => 'Member not found.',
        ],
        'payment_status' => [
            'required' => 'Payment status is required.',
            'integer' => 'Please enter a numeric member status.',
        ],
    ],
];
