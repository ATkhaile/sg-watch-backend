<?php

return [
    'list' => [
        'message' => 'Coupons list retrieved successfully',
    ],
    'detail' => [
        'message' => 'Coupon detail retrieved successfully',
    ],
    'update' => [
        'success' => 'Coupon updated successfully',
        'failed' => 'Failed to update coupon',
    ],
    'create' => [
        'success' => 'Coupon created successfully',
        'failed' => 'Failed to create coupon',
    ],
    'delete' => [
        'success' => 'Coupon deleted successfully',
        'failed' => 'Failed to delete coupon',
    ],
    'validation' => [
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
        ],
        'search' => [
            'string' => 'Search must be a string',
            'max' => 'Search cannot exceed 255 characters',
        ],
        'status' => [
            'in' => 'Status is invalid',
            'required' => 'Status is required',
        ],
        'name' => [
            'required' => 'Name is required',
            'string' => 'Name must be a string',
            'max' => 'Name cannot exceed 255 characters',
        ],
        'coupon_type' => [
            'required' => 'Coupon type is required',
            'in' => 'Coupon type is invalid',
        ],
        'discount' => [
            'required' => 'Discount is required',
            'integer' => 'Discount must be an integer',
            'min' => 'Discount must be at least 0',
            'max_ratio' => 'Discount cannot exceed 100 when coupon type is ratio',
        ],
        'target_user' => [
            'required' => 'Target user is required',
            'in' => 'Target user is invalid',
        ],
        'limit_type' => [
            'required' => 'Limit type is required',
            'in' => 'Limit type is invalid',
        ],
        'limit' => [
            'required_if' => 'Limit is required when limit type is limit',
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
            'regex' => 'Limit must be a valid number',
            'numeric' => 'Limit must be numeric',
            'max' => 'Limit cannot exceed 999999',
        ],
        'expire_type' => [
            'required' => 'Expire type is required',
            'in' => 'Expire type is invalid',
        ],
        'expire_start_date' => [
            'required_if' => 'Start date is required when expire type is expire',
            'date_format' => 'Start date must be in Y/m/d format',
        ],
        'expire_end_date' => [
            'required_if' => 'End date is required when expire type is expire',
            'date_format' => 'End date must be in Y/m/d format',
            'after_or_equal' => 'End date must be after or equal to start date',
        ],
        'discount_option_type' => [
            'required' => 'Discount option type is required',
            'in' => 'Discount option type is invalid',
        ],
        'account_use_type' => [
            'required' => 'Account use type is required',
            'in' => 'Account use type is invalid',
        ],
        'maximum_account' => [
            'required_if' => 'Maximum account is required when account use type is count use',
            'integer' => 'Maximum account must be an integer',
            'min' => 'Maximum account must be at least 1',
            'regex' => 'Maximum account must be a valid number',
            'numeric' => 'Maximum account must be numeric',
            'max' => 'Maximum account cannot exceed 999999',
        ],
        'shop_id' => [
            'required' => 'Shop ID is required',
            'integer' => 'Shop ID must be an integer',
            'exists' => 'Shop ID does not exist',
        ],
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'ID does not exist',
        ],
    ],
];
