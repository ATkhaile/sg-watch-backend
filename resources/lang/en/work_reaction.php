<?php

return [
    'save' => [
        'success' => 'Work reaction saved successfully.',
        'failed' => 'Failed to save work reaction.',
    ],
    'validation' => [
        'work_management_id' => [
            'required' => 'The work management ID field is required.',
            'integer' => 'The work management ID must be an integer.',
            'exists' => 'The specified work management ID does not exist.',
        ],
        'reaction' => [
            'required' => 'The reaction field is required.',
            'in' => 'The selected reaction is invalid.',
        ],
    ],
];