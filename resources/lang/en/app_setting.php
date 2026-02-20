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
        'per_page' => [
            'integer' => 'The per page must be an integer.',
            'min' => 'The per page must be at least 1.',
            'max' => 'The per page may not be greater than 100.',
        ],
        'search' => [
            'string' => 'The search must be a string.',
            'max' => 'The search may not be greater than 255 characters.',
        ],
        'sort' => [
            'string' => 'The sort must be a string.',
            'in' => 'The selected sort field is invalid.',
        ],
        'direction' => [
            'in' => 'The direction must be either ASC or DESC.',
        ],
        'type' => [
            'required' => 'The type is required.',
            'string' => 'The type must be a string.',
            'in' => 'The selected type is invalid.',
        ],
        'name' => [
            'required' => 'The name is required.',
            'string' => 'The name must be a string.',
            'max' => 'The name may not be greater than 255 characters.',
        ],
        'icon' => [
            'required' => 'The icon is required.',
            'image' => 'The icon must be an image.',
            'mimes' => 'The icon must be a file of type: jpeg, png, jpg, gif, svg.',
            'max' => 'The icon may not be greater than 100 megabytes.',
        ],
        'link' => [
            'required' => 'The link is required.',
            'url' => 'The link must be a valid URL.',
            'max' => 'The link may not be greater than 255 characters.',
        ],
        'order_num' => [
            'required' => 'The order number is required.',
            'integer' => 'The order number must be an integer.',
            'min' => 'The order number must be at least 0.',
        ],
        'id' => [
            'required' => 'The ID is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
        'user_id' => [
            'required' => 'The user ID is required.',
            'integer' => 'The user ID must be an integer.',
            'exists' => 'The selected user ID is invalid.',
        ],
        'app_show_name' => [
            'required' => 'The app show name is required.',
            'string' => 'The app show name must be a string.',
            'max' => 'The app show name may not be greater than 255 characters.',
        ],
        'settings' => [
            'required' => 'The settings field is required.',
            'array' => 'The settings field must be an array.',
            'min' => 'The settings field must have at least one item.',
            'id' => [
                'required' => 'Each setting must have an ID.',
                'integer' => 'Each setting ID must be an integer.',
                'exists' => 'One or more setting IDs are invalid.',
            ],
            'order_num' => [
                'required' => 'Each setting must have an order number.',
                'integer' => 'Each setting order number must be an integer.',
                'min' => 'Each setting order number must be at least 0.',
            ],
        ],
        'app_show_name_jp' => [
            'string' => 'The app show name jp must be a string.',
            'max' => 'The app show name jp may not be greater than 255 characters.',
        ],
        'app_show_name_other' => [
            'string' => 'The app show name other must be a string.',
            'max' => 'The app show name other may not be greater than 255 characters.',
        ],
        'lang' => [
            'max' => 'The lang may not be greater than 255 characters.',
        ],
        'public_flag' => [
            'boolean' => 'The public flag must be a boolean.',
        ],
        'footer_type' => [
            'in' => 'The selected footer type is invalid.',
        ],
        'webview_type' => [
            'in' => 'The selected webview type is invalid.',
        ],
    ],
    'list' => [
        'message' => 'App Settings List',
    ],
    'find' => [
        'message' => 'App Setting detail',
    ],
    'save' => [
        'success' => 'App Setting created successfully.',
        'failed' => 'Failed to create App Setting.',
    ],
    'update' => [
        'success' => 'App Setting updated successfully.',
        'failed' => 'Failed to update App Setting.',
    ],
    'delete' => [
        'success' => 'App Setting deleted successfully.',
        'failed' => 'Failed to delete App Setting.',
    ],
    'info' => [
        'message' => 'App Setting information',
    ],
    'reset_order' => [
        'success' => 'App Settings order reset successfully.',
        'failed' => 'Failed to reset App Settings order.',
    ],
    'set_default' => [
        'success' => 'App Settings set as default successfully.',
        'failed' => 'Failed to set App Settings as default.',
    ],
    'update_order' => [
        'success' => 'App Settings order updated successfully.',
        'failed' => 'Failed to update App Settings order.',
    ],
    'dark_mode' => [
        'enabled'  => 'Dark mode enabled.',
        'disabled' => 'Dark mode disabled.',
        'info'   => 'Fetched dark mode state.',

    ],
];
