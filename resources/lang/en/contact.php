<?php

return [
    'create' => [
        'success' => 'Contact created successfully',
        'failed' => 'Failed to create contact',
    ],
    'validation' => [
        'email' => [
            'required' => 'Email is required',
            'email' => 'Please enter a valid email address',
            'max' => 'Email must not exceed 255 characters',
        ],
        'last_name_kanji' => [
            'required' => 'Last name (Kanji) is required',
            'string' => 'Last name (Kanji) must be a string',
            'max' => 'Last name (Kanji) must not exceed 255 characters',
        ],
        'first_name_kanji' => [
            'required' => 'First name (Kanji) is required',
            'string' => 'First name (Kanji) must be a string',
            'max' => 'First name (Kanji) must not exceed 255 characters',
        ],
        'first_name_kana' => [
            'required' => 'First name (Kana) is required',
            'string' => 'First name (Kana) must be a string',
            'max' => 'First name (Kana) must not exceed 255 characters',
        ],
        'last_name_kana' => [
            'required' => 'Last name (Kana) is required',
            'string' => 'Last name (Kana) must be a string',
            'max' => 'Last name (Kana) must not exceed 255 characters',
        ],
        'contact_type' => [
            'required' => 'Contact type is required',
            'integer' => 'Contact type must be an integer',
            'min' => 'Contact type must be at least 1',
        ],
        'birthday' => [
            'required' => 'Birthday is required',
            'date_format' => 'Birthday must be in Y/m/d format',
        ],
        'content' => [
            'required' => 'Content is required',
            'string' => 'Content must be a string',
            'max' => 'Content must not exceed 1000 characters',
        ],
    ],
];
