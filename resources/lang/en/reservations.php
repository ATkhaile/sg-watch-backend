<?php

return [
    'list' => [
        'message' => 'Reservation list',
        'success' => 'Fetched reservation list.',
    ],
    'detail' => [
        'message' => 'Reservation detail',
        'success' => 'Fetched reservation detail.',
    ],
    'reservation' => [
        'not_found' => 'Reservation not found or already processed'
    ],
    'cancel' => [
        'success' => 'Reservation canceled successfully by admin',
        'failure' => 'Failed to cancel reservation',
        'not_found' => 'Reservation not found or already processed'
    ],
    'id' => [
        'required' => 'Reservation ID is required.',
        'integer' => 'Reservation ID must be an integer.',
        'exists' => 'The specified reservation does not exist for this shop.'
    ],
    'validation' => [
        'start_date' => [
            'required' => 'start_date is required.',
            'date_format' => 'start_date must be in YYYY-MM-DD format.',
        ],
        'end_date' => [
            'required' => 'end_date is required.',
            'date_format'   => 'end_date must be in YYYY-MM-DD format.',
            'after_or_equal' => 'end_date must be after or equal to start_date.',
        ],
        'date' => [
            'required'    => 'The date field is required.',
            'date_format' => 'date must be in YYYY-MM-DD format.',
        ],
        'month' => [
            'required'    => 'The month field is required.',
            'date_format' => 'month must be in YYYY-MM format.',
        ],
        'reservation_number' => [
            'string' => 'reservation_number must be a string.',
            'max'    => 'reservation_number may not be greater than 255 characters.',
        ],
        'shop_id' => [
            'required' => 'shop_id is required.',
            'integer'  => 'shop_id must be an integer.',
            'exists'   => 'The shop does not exist.',
        ],
        'id' => [
            'required' => 'id is required.',
            'integer'  => 'id must be an integer.',
            'exists'   => 'id does not exist.',
        ],
    ],
];
