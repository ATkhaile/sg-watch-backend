<?php

return [
    'role' => [
        'create' => [
            'success' => 'Role created successfully',
            'failed' => 'Failed to create role'
        ],
        'update' => [
            'success' => 'Role updated successfully',
            'failed' => 'Failed to update role'
        ],
        'delete' => [
            'success' => 'Role deleted successfully',
            'failed' => 'Failed to delete role',
            'system_role_cannot_be_deleted' => 'System roles cannot be deleted'
        ],
        'list' => [
            'message' => 'Role list',
        ],
        'find' => [
           'message' => 'Role detail',
        ],
        'not_found' => 'Role not found',
        'validation' => [
            'id' => [
               'required' => 'ID is required',
               'integer' => 'ID must be an integer',
               'exists' => 'Role not found'
            ],
            'name' => [
                'required' => 'Name is required',
                'unique' => 'Name already exists',
                'regex' => 'Name cannot contain spaces',
                'string' => 'Name must be a string',
                'max' => 'Name must not exceed 255 characters'
            ],
            'search_name' => [
                'max' => 'Search name must not exceed 255 characters'
            ],
            'search_name_like' => [
                'boolean' => 'Search name like must be a boolean'
            ],
            'search_name_not' => [
                'boolean' => 'Search name not must be a boolean'
            ],
            'search_domain' => [
                'max' => 'Search domain must not exceed 255 characters'
            ],
            'search_domain_like' => [
                'boolean' => 'Search domain like must be a boolean'
            ],
            'search_domain_not' => [
                'boolean' => 'Search domain not must be a boolean'
            ],
            'display_name' => [
                'required' => 'Display name is required',
                'string' => 'Display name must be a string',
                'max' => 'Display name must not exceed 255 characters'
            ],
            'description' => [
                'string' => 'Description must be a string',
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
        ],
        'attach' => [
            'success' => 'Roles attached to user successfully',
            'failed' => 'Failed to attach roles to user'
        ],
        'detach' => [
            'success' => 'Roles detached from user successfully',
            'failed' => 'Failed to detach roles from user'
        ],
        'role_id' => [
            'required' => 'Role ID is required',
            'exists' => 'Role does not exist'
        ],
        'permission_ids'  => [
            'required' => 'Permission ID is required',
            'array' => 'Permission ID must be an array',
            'invalid' => 'Invalid permission ID: :values',
            'exists' => 'Permission does not exist'
        ],

    ],
    'permission' => [
        'create' => [
            'success' => 'Permission created successfully',
            'failed' => 'Failed to create permission'
        ],
        'update' => [
            'success' => 'Permission updated successfully',
            'failed' => 'Failed to update permission'
        ],
        'delete' => [
            'success' => 'Permission deleted successfully',
            'failed' => 'Failed to delete permission'
        ],
        'list' => [
            'message' => 'Permission list',
        ],
        'find' => [
           'message' => 'Permission detail',
        ],
        'not_found' => 'Permission not found',
        'validation' => [
            'id' => [
               'required' => 'ID is required',
               'integer' => 'ID must be an integer',
               'exists' => 'Permission not found'
            ],
            'name' => [
                'required' => 'Name is required',
                'unique' => 'Name already exists',
                'regex' => 'Name cannot contain spaces',
                'string' => 'Name must be a string',
                'max' => 'Name must not exceed 255 characters'
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
        ],
        'attach' => [
            'success' => 'Permissions attached to user successfully',
            'failed' => 'Failed to attach permissions to user'
        ],
        'detach' => [
            'success' => 'Permissions detached from user successfully',
            'failed' => 'Failed to detach permissions from user'
        ],

    ],
    'user' => [
        'user_id' => [
            'required' => 'User ID is required',
            'exists' => 'User does not exist'
        ],
        'permission_ids'  => [
            'required' => 'Permission ID is required',
            'array' => 'Permission ID must be an array',
            'invalid' => 'Invalid permission ID: :values',
            'exists' => 'Permission does not exist'
        ],
        'role_ids'  => [
            'required' => 'Role ID is required',
            'array' => 'Role ID must be an array',
            'invalid' => 'Invalid role ID: :values',
            'exists' => 'Role does not exist'
        ],
    ]
];
