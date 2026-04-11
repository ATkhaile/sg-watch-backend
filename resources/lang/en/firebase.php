<?php

return [
    'list' => [
        'message' => 'Firebase notifications retrieved successfully',
    ],
    'detail' => [
        'message' => 'Firebase notification retrieved successfully',
        'not_found' => 'Notification not found',
    ],
    'update_readed' => [
        'success' => 'Notification marked as read successfully',
        'failed' => 'Failed to mark notification as read',
    ],
    'unread' => [
        'message' => 'Unread notifications retrieved successfully',
    ],
    'validation' => [
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
        ],
        'fcm_token' => [
            'required' => 'FCM token is required',
            'string' => 'FCM token must be a string',
            'max' => 'FCM token must not exceed 255 characters',
            'exists' => 'FCM token does not exist',
        ],
        'notification_id' => [
            'required' => 'Notification ID is required',
            'integer' => 'Notification ID must be an integer',
            'exists' => 'Notification does not exist',
        ],
        'is_read' => [
            'boolean' => 'is_read must be true or false',
        ],
    ],
];
