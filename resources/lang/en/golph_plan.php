<?php

return [
    'list' => [
        'message' => 'Plan list retrieved successfully.',
        'page' => [
            'integer' => 'Page must be an integer.',
            'min' => 'Page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer.',
            'min' => 'Limit must be at least 1.',
        ],
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

    'validation' => [
        'shop_id' => [
            'required' => 'Shop ID is required.',
            'integer' => 'Shop ID must be an integer.',
            'exists' => 'The selected shop does not exist.',
        ],
        'id' => [
            'required' => 'Plan ID is required.',
            'integer' => 'Plan ID must be an integer.',
            'exists' => 'The selected plan does not exist.',
        ],
        'plan_id' => [
            'required' => 'Plan ID is required.',
            'integer' => 'Plan ID must be an integer.',
            'exists' => 'The selected plan does not exist.',
        ],
        'name' => [
            'required' => 'Plan name is required.',
            'string' => 'Plan name must be a string.',
            'max' => 'Plan name must not exceed 255 characters.',
        ],
        'status' => [
            'required' => 'Status is required.',
            'in' => 'The selected status is invalid.',
        ],
        'price' => [
            'required' => 'Price is required.',
            'regex' => 'Price must contain only numbers.',
            'numeric' => 'Price must be a number.',
            'min' => 'Price must be at least 0.',
            'max' => 'Price must not exceed 999999.',
        ],
        'highline_display' => [
            'required' => 'Highline display is required.',
            'string' => 'Highline display must be a string.',
            'max' => 'Highline display must not exceed 255 characters.',
        ],
        'available_reservation' => [
            'required' => 'Available reservation is required.',
            'array' => 'Available reservation must be an array.',
            'min' => 'At least one reservation option must be selected.',
            'in' => 'The selected reservation option is invalid.',
        ],
        'start_time' => [
            'required' => 'Start time is required.',
            'date_format' => 'Start time must be in HH:MM format.',
        ],
        'end_time' => [
            'required' => 'End time is required.',
            'date_format' => 'End time must be in HH:MM format.',
            'after' => 'End time must be after start time.',
        ],
        'available_from_type' => [
            'required' => 'Available from type is required.',
            'in' => 'The selected available from type is invalid.',
        ],
        'available_from_value' => [
            'required' => 'Available from value is required.',
            'regex' => 'Available from value must contain only numbers.',
            'numeric' => 'Available from value must be a number.',
            'min' => 'Available from value must be at least 1.',
            'max' => 'Available from value must not exceed 999999.',
        ],
        'available_from_unit' => [
            'required' => 'Available from unit is required.',
            'in' => 'The selected available from unit is invalid.',
            'custom' => 'Available from unit is invalid for the selected type.',
            'reservation_slots' => 'Available from unit must be minute or hour when type is reservation slots.',
            'reservation_date' => 'Available from unit must be day or month when type is reservation date.',
        ],
        'available_to_type' => [
            'required' => 'Available to type is required.',
            'in' => 'The selected available to type is invalid.',
        ],
        'available_to_value' => [
            'required' => 'Available to value is required.',
            'regex' => 'Available to value must contain only numbers.',
            'numeric' => 'Available to value must be a number.',
            'min' => 'Available to value must be at least 1.',
            'max' => 'Available to value must not exceed 999999.',
        ],
        'available_to_unit' => [
            'required' => 'Available to unit is required.',
            'in' => 'The selected available to unit is invalid.',
            'reservation_slots' => 'Available to unit must be minus or hour when type is reservation slots.',
            'reservation_date' => 'Available to unit must be day or month when type is reservation date.',
        ],
        'accompanying_slots' => [
            'required' => 'Accompanying slots is required.',
            'integer' => 'Accompanying slots must be an integer.',
            'min' => 'Accompanying slots must be at least 1.',
            'max' => 'Accompanying slots must not exceed 3.',
        ],
        'no_limit' => [
            'required' => 'No limit field is required.',
            'boolean' => 'No limit must be true or false.',
        ],
        'limit' => [
            'required' => 'Limit is required when no limit is false.',
            'regex' => 'Limit must contain only numbers.',
            'numeric' => 'Limit must be a number.',
            'min' => 'Limit must be at least 1.',
            'max' => 'Limit must not exceed 999999.',
        ],
        'charge_people_price' => [
            'required' => 'Charge people price is required.',
            'regex' => 'Charge people price must contain only numbers.',
            'numeric' => 'Charge people price must be a number.',
            'min' => 'Charge people price must be at least 1.',
            'max' => 'Charge people price must not exceed 999999.',
        ],
        'charge_time_price_1' => [
            'required' => 'Charge time price 1 is required.',
            'regex' => 'Charge time price 1 must contain only numbers.',
            'numeric' => 'Charge time price 1 must be a number.',
            'min' => 'Charge time price 1 must be at least 1.',
            'max' => 'Charge time price 1 must not exceed 999999.',
        ],
        'charge_time_price_2' => [
            'required' => 'Charge time price 2 is required.',
            'regex' => 'Charge time price 2 must contain only numbers.',
            'numeric' => 'Charge time price 2 must be a number.',
            'min' => 'Charge time price 2 must be at least 1.',
            'max' => 'Charge time price 2 must not exceed 999999.',
        ],
        'charge_time_price_3' => [
            'required' => 'Charge time price 3 is required.',
            'regex' => 'Charge time price 3 must contain only numbers.',
            'numeric' => 'Charge time price 3 must be a number.',
            'min' => 'Charge time price 3 must be at least 1.',
            'max' => 'Charge time price 3 must not exceed 999999.',
        ],
    ],
];
