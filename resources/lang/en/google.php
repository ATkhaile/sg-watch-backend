<?php

return [
    'callback' => [
        'success' => 'Login successful.',
        'failed' => 'An error occurred.',
        'invalid_code' => 'Invalid authorization code',
        'token_failed' => 'Failed to get access token from Google',
        'user_info_failed' => 'Failed to get user information from Google',
    ],
    'validation' => [
        'code' => [
            'required' => 'Authorization code is required',
            'string' => 'Authorization code must be a string',
        ],
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
