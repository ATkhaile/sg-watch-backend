<?php

return [
    'create' => [
        'success' => 'Users created successfully',
        'failed' => 'Failed to create users'
    ],
    'update' => [
        'success' => 'Users updated successfully',
        'failed' => 'Failed to update users'
    ],
    'delete' => [
        'success' => 'Users deleted successfully',
        'failed' => 'Failed to delete users'
    ],
    'list' => [
        'message' => 'Users list',
    ],
    'find' => [
        'message' => 'Users detail',
    ],
    'not_found' => 'Users not found',
    'options' => [
        'message' => 'Users options list',
    ],
    'validation' => [
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'Users not found'
        ],
        'first_name' => [
            'required' => 'First name is required',
            'string' => 'First name must be a string',
            'max' => 'First name must not exceed 50 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'string' => 'Last name must be a string',
            'max' => 'Last name must not exceed 50 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'email' => 'Email must be a valid email address',
            'unique' => 'Email already exists',
        ],
        'password' => [
            'required' => 'Password is required',
            'string' => 'Password must be a string',
            'min' => 'Password must be at least 8 characters',
        ],
        'confirm_password' => [
            'required' => 'Confirm password is required',
            'string' => 'Confirm password must be a string',
            'same' => 'Confirm password must match password'
        ],
        'gender' => [
            'in' => 'Gender must be male, female, other, or unknown'
        ],
        'search' => [
            'max' => 'Search must not exceed 255 characters'
        ],
        'search_email' => [
            'max' => 'Search email must not exceed 255 characters'
        ],
        'search_email_like' => [
            'boolean' => 'Search email like must be a boolean'
        ],
        'search_email_not' => [
            'boolean' => 'Search email not must be a boolean'
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
        'sort_first_name' => [
            'in' => 'Sort first name must be ASC or DESC'
        ],
        'sort_email' => [
            'in' => 'Sort email must be ASC or DESC'
        ],
        'sort_created' => [
            'in' => 'Sort created must be ASC or DESC'
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be ASC or DESC'
        ],
        'admin' => [
            'boolean' => 'Admin must be true or false'
        ]
    ]
];
