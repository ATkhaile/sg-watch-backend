<?php

return [
    'job' => [
        'list' => [
            'message' => 'Import job list retrieved successfully',
        ],
        'detail' => [
            'message' => 'Import job detail retrieved successfully',
        ],
        'delete_error' => [
            'success' => 'Error jobs deleted successfully',
            'failed' => 'Failed to delete error jobs',
        ],
        'stop' => [
            'success' => 'Import job stopped successfully',
            'failed' => 'Failed to stop import job',
        ],
        'resume' => [
            'success' => 'Import job resumed successfully',
            'failed' => 'Failed to resume import job',
        ],
        'create' => [
            'success' => 'Import job created successfully',
            'failed' => 'Failed to create import job',
        ],
    ],
    'validation' => [
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
        'status' => [
            'string' => 'Status must be a string',
            'in' => 'Status must be one of: ' . implode(', ', \App\Enums\JobStatus::getValues())
        ],
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'Import job not found',
        ],
        'skip_error' => [
            'boolean' => 'skip_error must be true or false',
        ],
        'sanitize' => [
            'boolean' => 'sanitize must be true or false',
        ],
        'file' => [
            'required' => 'File is required',
            'file' => 'File must be a valid file',
            'mimes' => 'File must be a CSV file',
            'max' => 'File size must not exceed 10GB'
        ],
    ],
];
