<?php

return [
    'validation' => [
        'fcm_token' => [
            'required' => 'The FCM token is required.',
            'string' => 'The FCM token must be a string.',
            'max' => 'The FCM token may not be greater than 255 characters.',
        ],
        'user_id' => [
            'required' => 'User ID is required.',
            'integer'  => 'User ID must be an integer.',
            'exists'   => 'User does not exist.',
        ],
        'fcm_token_id' => [
            'required' => 'FCM token ID is required.',
            'integer'  => 'FCM token ID must be an integer.',
            'exists'   => 'FCM token does not exist.',
        ],
        'active_status' => [
            'required' => 'Active status is required.',
            'string'   => 'Active status must be a string.',
            'in'       => 'Active status must be active or deactive.',
        ],
        'device_name' => [
            'string' => 'Device name must be a string.',
            'max'    => 'Device name may not be greater than 255 characters.',
        ],
        'app_version_name' => [
            'string' => 'App version name must be a string.',
            'exists' => 'The specified app version does not exist.',
        ],
    ],
    'create' => [
        'successful' => 'FCM token created successfully.',
        'failed' => 'Failed to create FCM token.',
    ],
    'list' => [
        'message' => 'Device list retrieved successfully.',
    ],
    'update_status' => [
        'success' => 'FCM token status updated successfully.',
        'failed'  => 'Failed to update FCM token status.',
    ],
    'delete' => [
        'successful' => 'FCM token deleted successfully.',
        'failed' => 'Failed to delete FCM token.',
    ],
];
