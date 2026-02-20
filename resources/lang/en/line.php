<?php

return [
    'validation' => [
        'code' => [
            'required' => 'The authorization code is required.',
            'string' => 'The authorization code must be a string.',
        ],
        'token' => [
            'required' => 'The token is required.',
            'string' => 'The token must be a string.',
        ],
        'type' => [
            'string' => 'The type must be a string.',
            'in' => 'The selected type is invalid.',
        ],
    ],
    'login' => [
        'success' => 'Login successful.',
        'error' => 'An error occurred.',
        'no_email' => 'This LINE account cannot log in because it does not have an email address registered.',
    ],
    'link_account' => [
        'params_prepared' => 'Account linking parameters have been prepared.',
    ],
];
