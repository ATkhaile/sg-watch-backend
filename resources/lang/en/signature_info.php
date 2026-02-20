<?php

return [
    'validation' => [
        'page' => [
            'integer' => 'The page must be an integer.',
            'min' => 'The page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'The limit must be an integer.',
            'min' => 'The limit must be at least 1.',
        ],
        'search' => [
            'string' => 'The search value must be a string.',
            'max' => 'The search value may not be greater than 255 characters.',
        ],
        'sort' => [
            'in' => 'The selected sort field is invalid.',
        ],
        'direction' => [
            'in' => 'The direction must be ASC or DESC.',
        ],
        'id' => [
            'required' => 'The ID field is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
        'type' => [
            'required' => 'The type field is required.',
            'in' => 'The selected type is invalid.',
        ],
        'domain' => [
            'required' => 'The domain field is required.',
        ],
        'app_id' => [
            'required' => 'The app ID field is required.',
        ],
        'unlimit_expires' => [
            'required' => 'The unlimited expiration flag is required.',
            'boolean' => 'The unlimited expiration flag must be true or false.',
        ],
        'expired_at' => [
            'required' => 'The expiration date is required.',
            'date' => 'The expiration date must be a valid date.',
        ],
    ],

    'list' => [
        'message' => 'Signature info list.',
    ],
    'find' => [
        'message' => 'Signature info detail.',
    ],
    'create' => [
        'success' => 'Created successfully.',
        'failed' => 'Failed to create.',
    ],
    'update' => [
        'success' => 'Updated successfully.',
        'failed' => 'Failed to update.',
    ],
    'delete' => [
        'success' => 'Deleted successfully.',
        'failed' => 'Failed to delete.',
    ],
];
