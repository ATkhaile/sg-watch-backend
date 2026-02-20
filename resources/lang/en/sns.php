<?php

return [
    'update' => [
        'success' => 'SNS information updated successfully',
        'failed' => 'Failed to update SNS information',
    ],
    'validation' => [
        'name' => [
            'required' => 'Name is required',
            'string' => 'Name must be a string',
            'max' => 'Name must not exceed 255 characters',
        ],
        'invite_code' => [
            'required' => 'Invite code is required',
            'string' => 'Invite code must be a string',
            'max' => 'Invite code must not exceed 255 characters',
            'unique' => 'This invite code is already taken',
        ],
        'invite_code_used' => [
            'string' => 'Invite code must be a string',
            'max' => 'Invite code must not exceed 255 characters',
            'exists' => 'Invalid invite code',
            'already_has_inviter' => 'You already have an inviter',
            'cannot_use_own_code' => 'You cannot use your own invite code',
        ],
    ],
];