<?php

return [
    'create' => [
        'success' => 'Chat created successfully',
        'failed' => 'Failed to create chat'
    ],
    'reply' => [
        'success' => 'Chat reply successfully',
        'failed' => 'Failed to reply chat'
    ],
    'list' => [
        'message' => 'Chat list',
    ],
    'not_found' => 'Chat not found',
    'invalid' => 'Invalid chat mode',
    'validation' => [
        'email' => [
           'required' => 'email is required',
           'email' => 'email must be an email',
        ],
        'name' => [
           'required' => 'Name is required',
           'string' => 'Name must be a string',
        ],
        'code' => [
           'required' => 'Code is required',
           'string' => 'Code must be a string',
           'size' => 'Code must be between 1 and 8 characters',
           'not_exists' => 'Code does not exist',
        ],
        'message' => [
            'required' => 'Message is required',
            'string' => 'Message must be a string',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
    ],
    'login' => [
        'success' => 'Login successfully',
        'failed' => 'Login failed'
    ],
    'session' => [
        'success' => 'Chat room created successfully, code sent via email.',
        'exists' => 'A chat room already exists for this email.',
        'error' => 'Failed to chat room',
    ],
];
