<?php

return [
    'validation' => [
        'line_user_id' => [
            'required' => 'Line User ID is required.',
            'string' => 'Line User ID must be a string.',
        ],
        'display_name' => [
            'required' => 'Display name is required.',
            'string' => 'Display name must be a string.',
        ],
        'picture_url' => [
            'url' => 'Picture URL must be a valid URL.',
        ],
        'type' => [
            'string' => 'The type must be a string.',
            'in' => 'The selected type is invalid.',
        ],
    ],
    'login' => [
        'success' => 'Line Mini login successful.',
        'error' => 'An error occurred during Line Mini login.',
    ],
    'callback' => [
        'success' => 'Line Mini callback processed successfully.',
        'error' => 'An error occurred during Line Mini callback.',
    ],
];