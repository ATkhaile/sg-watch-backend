<?php

return [
    'create' => [
        'success' => 'Dify created successfully',
        'failed' => 'Failed to create plans'
    ],
    'update' => [
        'success' => 'Dify updated successfully',
        'failed' => 'Failed to update plans'
    ],
    'delete' => [
        'success' => 'Dify deleted successfully',
        'failed' => 'Failed to delete plans'
    ],
    'list' => [
        'message' => 'Dify list',
    ],
    'find' => [
       'message' => 'Dify detail',
    ],
    'user_id' => [
        'required' => 'Dify is required',
        'integer' => 'Dify must be an integer',
        'exists' => 'Dify not found',
    ],
    'not_found' => 'Dify not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'Dify not found'
        ],
        'email' => [
            'required' => 'Name is required',
            'email' => 'Email must be a valid email address',
        ],
        'password' => [
            'required' => 'Password is required',
            'string' => 'Password must be a string',
        ],
        'service_name' => [
            'required' => 'Service name is required',
            'string' => 'Service name must be a string',
        ],
        'service_description' => [
            'required' => 'Service description is required',
            'string' => 'Service description must be a string',
        ],
        'usage_description' => [
            'required' => 'Usage description is required',
            'string' => 'Usage description must be a string',
        ],
        'pricing_plan' => [
            'required' => 'Pricing plan is required',
            'string' => 'Pricing plan must be a string',
        ],
        'usage_limit' => [
            'required' => 'Usage limit is required',
            'string' => 'Usage limit must be a string',
        ],
        'supported_env' => [
            'required' => 'Supported environment is required',
            'string' => 'Supported environment must be a string',
        ],
        'faq' => [
            'required' => 'FAQ is required',
            'string' => 'FAQ must be a string',
        ],
        'search_name' => [
            'max' => 'Search title must not exceed 255 characters'
        ],
        'search_name_like' => [
            'boolean' => 'Search title like must be a boolean'
        ],
        'search_name_not' => [
            'boolean' => 'Search title not must be a boolean'
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
