<?php

return [
    'callback' => [
        'success' => 'Login successful.',
        'failed' => 'An error occurred.',
        'user_info_failed' => 'Failed to get user information from Facebook',
    ],
    'validation' => [
        'token' => [
            'required' => 'Token is required',
            'string' => 'Token must be a string',
        ],
        'type' => [
            'in' => 'The selected type is invalid',
            'string' => 'Type must be a string',
        ],
    ],
];