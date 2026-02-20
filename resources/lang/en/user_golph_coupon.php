<?php

return [
    'list' => [
        'message' => 'User coupons retrieved successfully.',
    ],
    'validation' => [
        'shop_id' => [
            'required' => 'The shop ID is required.',
            'integer'  => 'The shop ID must be an integer.',
            'exists'   => 'The selected shop does not exist.',
        ],
        'user_type' => [
            'required' => 'The user_type field is required.',
            'integer'  => 'The user_type must be an integer.',
            'in'       => 'The selected user_type is invalid.',
        ],
    ],
];
