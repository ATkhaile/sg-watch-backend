<?php

return [
    'get' => [
        'message' => 'Invite information retrieved successfully',
    ],
    'create' => [
        'success' => 'Invite accepted successfully',
        'failed' => 'Failed to accept invite',
        'inviter_not_found' => 'Invalid invite code',
        'user_not_found' => 'User not found',
        'already_has_inviter' => 'You already have an inviter',
    ],
    'validation' => [
        'invite_code' => [
            'required' => 'Invite code is required',
            'string' => 'Invite code must be a string',
            'max' => 'Invite code must not exceed 255 characters',
            'exists' => 'Invalid invite code',
        ],
        'already_has_inviter' => 'You already have an inviter',
        'cannot_use_own_code' => 'You cannot use your own invite code',
    ],
];