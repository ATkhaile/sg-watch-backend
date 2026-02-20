<?php

return [
    'create' => [
        'success' => 'Stripe account created successfully',
        'failed' => 'Failed to create plans'
    ],
    'update' => [
        'success' => 'Stripe account updated successfully',
        'failed' => 'Failed to update plans'
    ],
    'delete' => [
        'success' => 'Stripe account deleted successfully',
        'failed' => 'Failed to delete plans'
    ],
    'list' => [
        'message' => 'Stripe account list',
    ],
    'find' => [
       'message' => 'Stripe account detail',
    ],
    'customers' => [
        'subscriptions' => [
            'message' => 'Customer subscriptions list',
            'active' => 'Activated',
            'inactive' => 'Not activated'
        ],
    ],
    'not_found' => 'Stripe account not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'Stripe account not found'
        ],
        'name' => [
            'required' => 'Name is required',
            'string' => 'Name must be a string',
            'max' => 'Name must not exceed 255 characters'
        ],
        'price' => [
            'required' => 'Price is required',
            'integer' => 'Price must be a integer'
        ],
        'plan_type' => [
            'required' => 'Plan type is required',
            'integer' => 'Plan type must be an integer'
        ],
        'sns_limits' => [
            'array' => 'SNS limits must be an array'
        ],
        'sns_developer' => [
            'array' => 'SNS developer must be an array'
        ],
        'stripe_plan_id' => [
            'required' => 'Stripe plan ID is required',
            'string' => 'Stripe plan ID must be a string'
        ],
        'stripe_key' => [
            'required' => 'Stripe key is required',
            'string' => 'Stripe key must be a string'
        ],
        'stripe_secret' => [
            'required' => 'Stripe secret is required',
            'string' => 'Stripe secret must be a string'
        ],
        'stripe_payment_link' => [
            'required' => 'Stripe payment link is required',
            'string' => 'Stripe payment link must be a string'
        ],
        'stripe_webhook_secret' => [
            'required' => 'Stripe webhook secret is required',
            'string' => 'Stripe webhook secret must be a string',
        ],
        'cancel_hours' => [
            'numeric' => 'Cancel hours must be a number',
            'min' => 'Cancel hours must be at least 0'
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
    'products' => [
        'message' => 'Products retrieved successfully'
    ],
    'prices' => [
        'message' => 'Prices retrieved successfully'
    ],
    'payment_links' => [
        'message' => 'Payment links retrieved successfully'
    ],
    'dashboard_stats' => [
        'refresh' => [
            'success' => 'Dashboard stats refreshed successfully',
            'failed' => 'Failed to refresh dashboard stats'
        ],
        'refresh_all' => [
            'success' => 'All dashboard stats refreshed successfully',
            'failed' => 'Failed to refresh all dashboard stats'
        ],
        'errors' => [
            'account_not_found' => 'Stripe account not found',
            'api_key_not_configured' => 'Stripe API key is not configured for this account'
        ]
    ],
    'sync' => [
        'backfill' => [
            'success' => 'Backfill completed successfully',
            'failed' => 'Backfill failed',
            'completed_with_errors' => 'Backfill completed with some errors'
        ],
        'incremental' => [
            'success' => 'Incremental sync completed successfully',
            'failed' => 'Incremental sync failed',
            'completed_with_errors' => 'Incremental sync completed with some errors'
        ],
        'errors' => [
            'unsupported_object_type' => 'Unsupported object type'
        ]
    ]
];
