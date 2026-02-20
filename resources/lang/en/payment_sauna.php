<?php

return [
    'update' => [
        'success' => 'Payment information updated successfully.',
        'failed' => 'Failed to update payment information.',
    ],
    'shop_id' => [
        'required' => 'Shop ID is required.',
        'integer' => 'Shop ID must be an integer.',
        'exists' => 'Selected shop does not exist.',
    ],
    'card_id' => [
        'required' => 'Card ID is required.',
        'integer' => 'Card ID must be an integer.',
        'exists' => 'Selected card does not exist.',
    ],
    'date' => [
        'required' => 'Date is required.',
        'date_format' => 'Date must be in Y-m-d format.',
        'after_or_equal' => 'Date must be today or in the future.',
        'unique' => 'This date is already reserved.',
    ],
    'option_type1_id' => [
        'exists' => 'Selected option type 1 is not available.',
    ],
    'option_type2_id' => [
        'exists' => 'Selected option type 2 is not available.',
    ],
    'parking_flag' => [
        'required' => 'Parking flag is required.',
        'in' => 'Parking flag must be 0 or 1.',
    ],
    'usage_option_id' => [
        'required' => 'Usage option is required.',
        'integer' => 'Usage option must be an integer.',
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
        'exists' => 'Selected prefecture does not exist.',
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
    'coupon_id' => [
        'in' => 'Selected coupon is not valid.',
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
    'success' => [
        'requires_action' => 'Payment requires additional action.',
        'completed' => 'Payment completed successfully.',
    ],
];