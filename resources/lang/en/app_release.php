<?php

return [
    'validation' => [
        'name' => [
            'required' => 'The name is required.',
            'string'   => 'The name must be a string.',
            'max'      => 'The name may not be greater than 255 characters.',
        ],
        'mode' => [
            'required' => 'The mode is required.',
            'string'   => 'The mode must be a string.',
            'in'       => 'The selected mode is invalid.',
        ],
        'required_update_flag' => [
            'required' => 'The required_update_flag is required.',
            'boolean'  => 'The required_update_flag must be boolean.',
        ],
        'app_store_link' => [
            'string' => 'The app_store_link must be a string.',
            'max'    => 'The app_store_link may not be greater than 500 characters.',
            'url'    => 'The app_store_link format is invalid.',
        ],
        'chplay_store_link' => [
            'string' => 'The chplay_store_link must be a string.',
            'max'    => 'The chplay_store_link may not be greater than 500 characters.',
            'url'    => 'The chplay_store_link format is invalid.',
        ],
        'app_versions' => [
            'required' => 'The app_versions field is required.',
            'array'    => 'The app_versions must be an array.',
        ],
        'version_name' => [
            'required' => 'The version_name is required.',
            'string'   => 'The version_name must be a string.',
            'max'      => 'The version_name may not be greater than 50 characters.',
            'exists'   => 'The specified version_name does not exist.',
        ],
        'release_date' => [
            'required'    => 'The release_date is required.',
            'date_format' => 'The release_date must match the format Y/m/d.',
        ],
        'min_version_flag' => [
            'required' => 'The min_version_flag is required.',
            'boolean'  => 'The min_version_flag must be boolean.',
        ],
    ],
    'get' => [
        'message' => 'App release information',
    ],
    'save' => [
        'success' => 'App release has been updated.',
        'failed'  => 'Failed to update app release.',
    ],
    'info' => [
        'message' => 'App release information retrieved successfully.',
    ],
];
