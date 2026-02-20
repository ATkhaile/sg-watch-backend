<?php

return [
    'list' => [
        'message' => 'Group list retrieved successfully.',
    ],
    'create' => [
        'success' => 'Group created successfully.',
        'failed'  => 'Failed to create group.',
    ],
    'find' => [
        'message' => 'Group details retrieved successfully.',
    ],
    'update' => [
        'success' => 'Group updated successfully.',
        'failed'  => 'Failed to update group.',
    ],
    'delete' => [
        'success' => 'Group deleted successfully.',
        'failed'  => 'Failed to delete group.',
    ],
    'pin' => [
        'success' => 'Group pin status toggled successfully.',
        'failed'  => 'Failed to toggle group pin status.',
    ],
    'hidden' => [
        'success' => 'Group hidden status toggled successfully.',
        'failed'  => 'Failed to toggle group hidden status.',
    ],
    'access_denied' => 'You do not have permission to access this group.',
    'join_request' => [
        'success' => 'Join request sent successfully. Please wait for admin approval.',
        'failed' => 'Failed to send join request. You may already have a pending request or already be a member.',
        'only_private_groups' => 'Join requests are only available for private groups.',
        'approved' => 'Join request approved successfully.',
        'rejected' => 'Join request rejected successfully.',
        'approve_failed' => 'Failed to approve join request.',
        'reject_failed' => 'Failed to reject join request.',
        'cancelled' => 'Join request cancelled successfully.',
        'cancel_failed' => 'Failed to cancel join request. Request not found or already processed.',
        'message_too_long' => 'Message may not be greater than 500 characters.',
        'invalid_status' => 'Status must be pending, approved, or rejected.',
        'request_id_required' => 'Request ID is required.',
        'request_not_found' => 'Join request not found.',
    ],
    'accept' => [
        'unauthorized' => 'You must be logged in to accept the invitation.',
        'invalid' => 'Invalid or expired invitation token.',
        'email_not_match' => 'This invitation is for a different email address.',
        'failed' => 'Failed to accept the invitation.',
        'success' => 'You have successfully joined the group.',
    ],
    'mail' => [
        'subject' => 'You have been invited to join a group',
    ],
    'invite' => [
        'invalid_token' => 'Invalid or expired invitation link.',
        'valid_token' => 'Invitation link is valid.',
    ],
    'force_add' => [
        'success' => ':count member(s) have been summoned to the group.',
        'failed' => 'Failed to summon members.',
        'already_members' => ':count specified user(s) are already members of the group.',
    ],
    'users' => [
        'list' => [
            'message' => 'Group members retrieved successfully.',
        ],
        'create' => [
            'success' => 'Member(s) added successfully.',
            'failed'  => 'Failed to add member(s).',
        ],
        'update' => [
            'success' => 'Member role updated successfully.',
            'failed'  => 'Failed to update member role.',
        ],
        'remove' => [
            'success' => 'Member removed successfully.',
            'failed'  => 'Failed to remove member.',
        ],
    ],
    'user_option' => [
        'list' => [
            'message' => 'User options retrieved successfully.',
        ],
    ],
    'access' => [
        'list' => ['message' => 'Access settings retrieved successfully.'],
        'upsert' => [
            'success' => 'Access settings saved.',
            'failed'  => 'Failed to save access settings.',
        ],
        'validation' => [
            'access_settings' => [
                'required' => 'Access settings are required.',
                'array'    => 'Access settings must be an array.',
                'min'      => 'At least one access setting is required.',
            ],
            'access_type' => [
                'required' => 'Access type is required.',
                'in'       => 'Access type is invalid.',
            ],
            'role_type' => [
                'required' => 'Role type is required.',
                'in'       => 'Role type is invalid.',
            ],
        ],
    ],

    'validation' => [
        'user_id' => [
            'required' => 'User ID is required.',
            'integer'  => 'User ID must be an integer.',
            'exists'   => 'User does not exist.',
        ],
        'group_id' => [
            'required' => 'Group ID is required.',
            'integer'  => 'Group ID must be an integer.',
            'exists'   => 'Group does not exist.',
        ],
        'name' => [
            'required' => 'Name is required.',
            'string'   => 'Name must be a string.',
            'max'      => 'Name may not be greater than 255 characters.',
        ],
        'description' => [
            'string' => 'Description must be a string.',
            'max'    => 'Description may not be greater than 1000 characters.',
        ],
        'page' => [
            'integer' => 'Page must be an integer.',
            'min'     => 'Page must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer.',
            'min'     => 'Limit must be at least 1.',
        ],
        'show_flag' => [
            'in' => 'Show flag must be either 0 or 1.',
        ],
        'search' => [
            'string' => 'Search must be a string.',
            'max'    => 'Search may not be greater than 255 characters.',
        ],
        'user_ids' => [
            'required' => 'User IDs are required.',
            'array'    => 'User IDs must be an array.',
            'min'      => 'At least one user ID is required.',
            'integer_each' => 'Each user ID must be an integer.',
            'exists_each'  => 'One or more specified user IDs do not exist.',
            'already_in_group'    => 'One or more specified users are already in the group.',
        ],
        'role_type' => [
            'required' => 'Role type is required.',
            'in'       => 'Invalid role type.',
        ],
        'emails' => [
            'array' => 'Emails must be an array.',
            'required' => 'Email is required.',
            'email' => 'Email must be a valid email address.',
            'max' => 'Email may not be greater than 255 characters.',
        ],
        'token' => [
            'required' => 'Token is required.',
            'string' => 'Token must be a string.',
            'max' => 'Token may not be greater than 255 characters.',
        ],
        'redirect_url' => [
            'required' => 'Redirect URL is required.',
            'url' => 'Redirect URL must be a valid URL.',
            'max' => 'Redirect URL may not be greater than 500 characters.',
        ],
        'one_of_required' => 'Either emails or user IDs are required.',
    ],
];
