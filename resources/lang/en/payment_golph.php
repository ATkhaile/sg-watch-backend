<?php

return [
    'update' => [
        'success' => 'Update successful.',
        'error' => 'Update failed.',
    ],
    'shop_id' => [
        'required' => 'Shop ID is required.',
        'integer' => 'Shop ID must be an integer.',
        'exists' => 'The selected shop does not exist.',
    ],
    'card_id' => [
        'required' => 'Card ID is required.',
        'integer' => 'Card ID must be an integer.',
        'exists' => 'The selected card does not exist.',
    ],
    'card_holder_name' => [
        'required' => 'Card holder name is required.',
        'max' => 'Card holder name must not exceed 255 characters.',
    ],
    'date' => [
        'required' => 'Date is required.',
        'date_format' => 'Date must be in Y-m-d format.',
        'unique' => 'This time slot is already reserved.',
        'unavailable' => 'This date is not available for reservation.',
    ],
    'start_time' => [
        'required' => 'Start time is required.',
        'date_format' => 'Start time must be in H:i format.',
    ],
    'option_type1_id' => [
        'required' => 'Number of users option is required.',
        'exists' => 'The selected number of users option is not valid.',
    ],
    'option_type2_id' => [
        'exists' => 'The selected time extension option is not valid.',
        'unavailable' => 'This time extension is not available.',
    ],
    'instructor_flag' => [
        'required' => 'Instructor flag is required.',
        'in' => 'Instructor flag must be 0 or 1.',
    ],
    'lesson_flag' => [
        'required' => 'Lesson flag is required.',
        'in' => 'Lesson flag must be 0 or 1.',
    ],
    'parking_flag' => [
        'required' => 'Parking flag is required.',
        'in' => 'Parking flag must be 0 or 1.',
    ],
    'card_holder_name' => [
        'required_without' => 'Card holder name is required when card ID is not provided.',
        'max' => 'Card holder name must not exceed 255 characters.',
    ],
    'billing_postal_code' => [
        'max' => 'Postal code must not exceed 8 characters.',
        'regex' => 'Postal code format is invalid.',
    ],
    'billing_prefecture_id' => [
        'integer' => 'Prefecture ID must be an integer.',
        'exists' => 'The selected prefecture does not exist.',
    ],
    'billing_city' => [
        'max' => 'City must not exceed 255 characters.',
    ],
    'billing_street_address' => [
        'max' => 'Street address must not exceed 255 characters.',
    ],
    'billing_building' => [
        'max' => 'Building must not exceed 255 characters.',
    ],
    'billing_tel' => [
        'max' => 'Phone number must not exceed 255 characters.',
    ],
    'token_id' => [
        'required_without' => 'Token ID is required when card ID is not provided.',
    ],
    'coupon_id' => [
        'in' => 'The selected coupon is not valid.',
    ],
    
    'errors' => [
        'invalid_lesson_time' => 'Invalid lesson time',
        'invalid_instructor_time' => 'Invalid instructor time',
        'invalid_reservation_date' => 'Invalid reservation date',
        'invalid_booking_time' => 'Invalid booking time',
        'invalid_customer_creation' => 'Invalid customer creation',
        'invalid_customer_update' => 'Invalid customer update',
        'invalid_card_addition' => 'Invalid card addition',
        'invalid_card_storage' => 'Invalid card storage',
        'payment_processing_failed' => 'Payment processing failed',
    ],
];