<?php

return [
    'validation' => [
        'code' => [
            'required' => 'The code field is required.',
            'string' => 'The code must be a string.',
        ],
    ],
    'login' => [
        'success' => 'Login successful.',
    ],
    'register' => [
        'success' => 'Registration successful.',
    ],
    'error' => [
        'line_token' => 'Failed to get Line access token.',
        'line_email' => 'This LINE account does not have an email address registered.',
        'line_linked' => 'This LINE account is already linked to another user.',
        'account_black' => 'Your account has been suspended. Please contact support via LINE.',
        'line_not_configured' => 'LINE_LOGIN_CHANNEL_ID or LINE_LOGIN_CALLBACK is missing in env file',
    ],
    'auth' => [
        'line_login_url_generated' => 'Line auth URL generated successfully',
    ],
];
