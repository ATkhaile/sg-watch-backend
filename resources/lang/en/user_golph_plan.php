<?php

return [
    'list' => [
        'message' => 'Plan list retrieved successfully.',
    ],
    'create' => [
        'success' => 'Plan created successfully.',
        'failed' => 'Failed to create plan.',
    ],
    'update' => [
        'success' => 'Plan updated successfully.',
        'failed' => 'Failed to update plan.',
    ],
    'detail' => [
        'success' => 'Plan details retrieved successfully.',
        'failed' => 'Failed to retrieve plan details.',
    ],
    'delete' => [
        'success' => 'Plan deleted successfully.',
        'failed' => 'Failed to delete plan.',
    ],
    'change' => [
        'success' => 'Plan changed successfully.',
        'failed' => 'Failed to change plan.',
    ],

    'validation' => [
        'shop_id' => [
            'required' => 'Shop ID is required.',
            'integer' => 'Shop ID must be an integer.',
            'exists' => 'The selected shop does not exist.',
        ],
        'plan_id' => [
            'required' => 'Plan ID is required.',
            'integer' => 'Plan ID must be an integer.',
            'exists' => 'The selected plan does not exist.',
        ],
    ],
];
