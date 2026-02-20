<?php

return [
    'list' => [
        'message' => 'Pusher notifications list retrieved successfully',
    ],
    'unread' => [
        'message' => 'Pusher unread notifications retrieved successfully',
    ],
    'update_readed' => [
        'success' => 'Pusher notification marked as read successfully',
        'failed' => 'Failed to mark pusher notification as read',
    ],
    'validation' => [
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
            'max' => 'Limit cannot exceed 100',
        ],
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'Notification not found',
        ],
    ],
];
