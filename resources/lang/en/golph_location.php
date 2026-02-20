<?php

return [
    'update' => [
        'success' => 'Golph location information has been updated.',
        'failed' => 'Failed to update golph location information.',
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
            'user_type' => [
                'required' => 'Option user type is required.',
                'required_if' => 'Option user type is required when option is active.',
                'in' => 'The selected option user type is invalid.',
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
                'image' => 'The file must be an image.',
                'mimes' => 'The image must be a file of type: :values.',
                'max' => 'The image may not be greater than :max kilobytes.',
            ],
        ],
        'instructor_flag_1' => [
            'required' => 'Instructor flag 1 is required.',
            'in' => 'The selected instructor flag 1 is invalid.',
        ],
        'instructor_price_1' => [
            'required_if' => 'Instructor price 1 is required when instructor flag 1 is enabled.',
            'numeric' => 'Instructor price 1 must be a number.',
            'min' => 'Instructor price 1 must be at least :min.',
            'max' => 'Instructor price 1 may not be greater than :max.',
            'regex' => 'Instructor price 1 format is invalid.',
        ],
        'lesson_flag_1' => [
            'required' => 'Lesson flag 1 is required.',
            'in' => 'The selected lesson flag 1 is invalid.',
        ],
        'lesson_price_1' => [
            'required_if' => 'Lesson price 1 is required when lesson flag 1 is enabled.',
            'numeric' => 'Lesson price 1 must be a number.',
            'min' => 'Lesson price 1 must be at least :min.',
            'max' => 'Lesson price 1 may not be greater than :max.',
            'regex' => 'Lesson price 1 format is invalid.',
        ],
        'instructor_flag_2' => [
            'required' => 'Instructor flag 2 is required.',
            'in' => 'The selected instructor flag 2 is invalid.',
        ],
        'instructor_price_2' => [
            'required_if' => 'Instructor price 2 is required when instructor flag 2 is enabled.',
            'numeric' => 'Instructor price 2 must be a number.',
            'min' => 'Instructor price 2 must be at least :min.',
            'max' => 'Instructor price 2 may not be greater than :max.',
            'regex' => 'Instructor price 2 format is invalid.',
        ],
        'lesson_flag_2' => [
            'required' => 'Lesson flag 2 is required.',
            'in' => 'The selected lesson flag 2 is invalid.',
        ],
        'lesson_price_2' => [
            'required_if' => 'Lesson price 2 is required when lesson flag 2 is enabled.',
            'numeric' => 'Lesson price 2 must be a number.',
            'min' => 'Lesson price 2 must be at least :min.',
            'max' => 'Lesson price 2 may not be greater than :max.',
            'regex' => 'Lesson price 2 format is invalid.',
        ],
        'instructor_flag_3' => [
            'required' => 'Instructor flag 3 is required.',
            'in' => 'The selected instructor flag 3 is invalid.',
        ],
        'instructor_price_3' => [
            'required_if' => 'Instructor price 3 is required when instructor flag 3 is enabled.',
            'numeric' => 'Instructor price 3 must be a number.',
            'min' => 'Instructor price 3 must be at least :min.',
            'max' => 'Instructor price 3 may not be greater than :max.',
            'regex' => 'Instructor price 3 format is invalid.',
        ],
        'lesson_flag_3' => [
            'required' => 'Lesson flag 3 is required.',
            'in' => 'The selected lesson flag 3 is invalid.',
        ],
        'lesson_price_3' => [
            'required_if' => 'Lesson price 3 is required when lesson flag 3 is enabled.',
            'numeric' => 'Lesson price 3 must be a number.',
            'min' => 'Lesson price 3 must be at least :min.',
            'max' => 'Lesson price 3 may not be greater than :max.',
            'regex' => 'Lesson price 3 format is invalid.',
        ],
        'visiter_flag_book' => [
            'required' => 'Visitor booking flag is required.',
            'in' => 'The selected visitor booking flag is invalid.',
        ],
        'lesson_setting_type' => [
            'required' => 'Lesson setting type is required.',
            'integer' => 'Lesson setting type must be an integer.',
            'in' => 'The selected lesson setting type is invalid.',
        ],
        'lesson_setting_value' => [
            'required' => 'Lesson setting value is required.',
            'regex' => 'Lesson setting value format is invalid.',
            'numeric' => 'Lesson setting value must be a number.',
            'min' => 'Lesson setting value must be at least :min.',
            'max' => 'Lesson setting value may not be greater than :max.',
        ],
        'lesson_setting_unit' => [
            'required' => 'Lesson setting unit is required.',
            'integer' => 'Lesson setting unit must be an integer.',
            'in' => 'The selected lesson setting unit is invalid.',
        ],
        'instructor_setting_type' => [
            'required' => 'Instructor setting type is required.',
            'integer' => 'Instructor setting type must be an integer.',
            'in' => 'The selected instructor setting type is invalid.',
        ],
        'instructor_setting_value' => [
            'required' => 'Instructor setting value is required.',
            'regex' => 'Instructor setting value format is invalid.',
            'numeric' => 'Instructor setting value must be a number.',
            'min' => 'Instructor setting value must be at least :min.',
            'max' => 'Instructor setting value may not be greater than :max.',
        ],
        'instructor_setting_unit' => [
            'required' => 'Instructor setting unit is required.',
            'integer' => 'Instructor setting unit must be an integer.',
            'in' => 'The selected instructor setting unit is invalid.',
        ],
    ],
];
