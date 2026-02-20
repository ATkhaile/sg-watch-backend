<?php

return [
    'list' => [
        'message' => 'Notifications list retrieved successfully',
    ],
    'detail' => [
        'message' => 'Notification detail retrieved successfully',
    ],
    'create' => [
        'success' => 'Notification created successfully',
        'failed' => 'Failed to create notification',
    ],
    'update' => [
        'success' => 'Notification updated successfully',
        'failed' => 'Failed to update notification',
    ],
    'delete' => [
        'success' => 'Notification deleted successfully',
        'failed' => 'Failed to delete notification',
    ],
    'validation' => [
        'title' => [
            'required' => 'Title is required',
            'string' => 'Title must be a string',
            'max' => 'Title cannot exceed 255 characters',
        ],
        'content' => [
            'required' => 'Content is required',
            'string' => 'Content must be a string',
            'max' => 'Content cannot exceed 10000 characters',
        ],
        'type' => [
            'required' => 'Push type is required',
            'string' => 'Push type must be a string',
            'max' => 'Push type cannot exceed 50 characters',
            'in' => 'Push type must be one of: ' . implode(', ', \App\Enums\PushType::getValues()),
        ],
        'push_datetime' => [
            'date_format' => 'Push datetime must be in format Y/m/d H:i:s',
        ],
        'push_now_flag' => [
            'boolean' => 'Push now flag must be true or false',
        ],
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'Notification not found',
        ],
        'image_file' => [
            'image' => 'Upload must be an image file',
            'max' => 'Upload file size cannot exceed 10MB',
        ],
        'sender_type' => [
            'required' => 'Sender type is required',
            'string' => 'Sender type must be a string',
            'max' => 'Sender type cannot exceed 50 characters',
            'in' => 'Sender type must be one of: ' . implode(', ', \App\Enums\SenderType::getValues()),
        ],
    ],
];
