<?php

return [
    'create' => [
        'success' => 'Media upload created successfully',
        'failed' => 'Failed to create media upload'
    ],
    'update' => [
        'success' => 'Media upload updated successfully',
        'failed' => 'Failed to update media upload'
    ],
    'delete' => [
        'success' => 'Media upload deleted successfully',
        'failed' => 'Failed to delete media upload'
    ],
    'streaming' => [
        'enabled' => 'Streaming enabled successfully',
        'disabled' => 'Streaming disabled successfully',
        'failed' => 'Failed to update streaming status'
    ],
    'list' => [
        'message' => 'Media uploads list',
    ],
    'find' => [
        'message' => 'Media upload detail',
    ],
    'not_found' => 'Media upload not found',
    'validation' => [
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'Media upload not found'
        ],
        'type' => [
            'required' => 'Type is required',
            'string' => 'Type must be a string',
            'in' => 'Type must be one of the following: ' . implode(', ', \App\Enums\FileType::all())
        ],
        'files' => [
            'required' => 'At least one file is required',
            'array' => 'Files must be an array',

        ],
        'file' => [
            'required' => 'File is required',
            'file' => 'File must be a valid file',
            'max' => 'File size must not exceed 10GB',
            'invalid_type' => 'File type is not valid for the specified type',
        ],
        'media_folder_id' => [
            'required' => 'Media folder is required',
            'integer' => 'Media folder must be an integer',
            'exists' => 'Media folder does not exist',
        ],
        'is_streaming' => [
            'required' => 'Streaming status is required',
            'boolean' => 'Streaming status must be a boolean',
        ],
    ],
];
