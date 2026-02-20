<?php

return [
    'create' => [
        'success' => 'MySnsBot created successfully',
        'failed' => 'Failed to create plans'
    ],
    'update' => [
        'success' => 'MySnsBot updated successfully',
        'failed' => 'Failed to update plans'
    ],
    'delete' => [
        'success' => 'MySnsBot deleted successfully',
        'failed' => 'Failed to delete plans'
    ],
    'list' => [
        'message' => 'MySnsBot list',
    ],
    'find' => [
       'message' => 'MySnsBot detail',
    ],
    'not_found' => 'MySnsBot not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'ID not found'
        ],
        'my_sns_id' => [
            'required' => 'MySnsBot is required',
            'integer' => 'MySnsBot must be an integer',
            'exists' => 'MySnsBot not found',
        ],
        'channel_id' => [
            'required' => 'Channel ID is required',
            'string' => 'Channel ID must be a string',
        ],
        'channel_secret' => [
            'required' => 'Channel secret is required',
            'string' => 'Channel secret must be a string',
        ],
        'channel_access_token' => [
            'required' => 'Channel access token is required',
            'string' => 'Channel access token must be a string',
        ],
        'search_my_sns_id' => [
            'max' => 'Search my_sns_id must not exceed 256 characters'
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
       'sort_name' => [
            'in' => 'Sort name must be ASC or DESC'
        ],
       'sort_created' => [
            'in' => 'Sort created must be ASC or DESC'
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be ASC or DESC'
        ]
    ]
];
