<?php

return [
    'update' => [
        'success' => 'Sauna location information has been updated.',
        'failed' => 'Failed to update sauna location information.',
    ],
    'validation' => [
        'location_id' => [
            'required' => 'Location ID is required.',
            'integer' => 'Location ID must be an integer.',
            'exists' => 'The selected location does not exist.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer' => 'ID must be an integer.',
            'exists' => 'The selected ID does not exist.',
        ],
        'name' => [
            'required' => 'Name is required.',
            'string' => 'Name must be a string.',
            'max' => 'Name may not be greater than :max characters.',
        ],
        'description' => [
            'required' => 'Description is required.',
        ],
        'line_id' => [
            'max' => 'Line ID may not be greater than :max characters.',
        ],
        'parking_flag' => [
            'required' => 'Parking flag is required.',
            'in' => 'The selected parking flag is invalid.',
        ],
        'parking_price' => [
            'required_if' => 'Parking price is required when parking flag is enabled.',
            'numeric' => 'Parking price must be a number.',
            'min' => 'Parking price must be at least :min.',
            'max' => 'Parking price may not be greater than :max.',
            'regex' => 'Parking price format is invalid.',
        ],
        'options' => [
            'required' => 'Options are required.',
            'array' => 'Options must be an array.',
            'name' => [
                'required_if' => 'Option name is required when option is active.',
                'string' => 'Option name must be a string.',
                'max' => 'Option name may not be greater than :max characters.',
            ],
            'price' => [
                'required_if' => 'Option price is required when option is active.',
                'numeric' => 'Option price must be a number.',
                'min' => 'Option price must be at least :min.',
                'max' => 'Option price may not be greater than :max.',
                'regex' => 'Option price format is invalid.',
            ],
            'type' => [
                'required' => 'Option type is required.',
                'in' => 'The selected option type is invalid.',
            ],
            'unit' => [
                'required' => 'Option unit is required.',
                'regex' => 'Option unit format is invalid.',
                'numeric' => 'Option unit must be a number.',
                'min' => 'Option unit must be at least :min.',
                'max' => 'Option unit may not be greater than :max.',
            ],
            'is_active' => [
                'required' => 'Option active status is required.',
                'in' => 'The selected option active status is invalid.',
            ],
        ],
        'images' => [
            'required' => 'Images are required.',
            'array' => 'Images must be an array.',
            'path' => [
                'image' => 'File must be an image.',
                'mimes' => 'Image must be a file of type: :values.',
                'max' => 'The image may not be greater than :max kilobytes.',
            ],
        ],
    ],
];
