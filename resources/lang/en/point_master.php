<?php

return [
    'daily_bonus' => [
        'message' => 'Daily bonus retrieved successfully.',
    ],
    'affiliate_bonus' => [
        'message' => 'Affiliate bonus retrieved successfully.',
    ],
    'save' => [
        'success' => 'Daily bonus saved successfully.',
        'failed' => 'Failed to save daily bonus.',
    ],
    'validation' => [
        'start_date' => [
            'required' => 'Start date is required.',
            'date_format' => 'Start date must be in Y/m/d H:i:s format.',
        ],
        'end_date' => [
            'date_format' => 'End date must be in Y/m/d H:i:s format.',
            'after_or_equal' => 'End date must be a date after or equal to start date.',
        ],
        'point' => [
            'required' => 'Point is required.',
            'integer' => 'Point must be an integer.',
            'min' => 'Point must be at least 0.',
            'max' => 'Point may not be greater than 999999999999.',
        ],
        'status' => [
            'in' => 'Status must be either on or off.',
        ],
        'inviter_point' => [
            'required' => 'Inviter point is required.',
            'integer' => 'Inviter point must be an integer.',
            'max' => 'Inviter point may not be greater than 999999999999.',
        ],
        'invitee_point' => [
            'required' => 'Invitee point is required.',
            'integer' => 'Invitee point must be an integer.',
            'max' => 'Invitee point may not be greater than 999999999999.',
        ],
    ],
];