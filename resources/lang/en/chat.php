<?php

return [
    'create' => [
        'success' => 'Chat created successfully',
        'failed' => 'Failed to create chat'
    ],
    'list' => [
        'message' => 'Chat list',
    ],
    'not_found' => 'Chat not found',
    'invalid' => 'Invalid chat mode',
    'online_status_updated' => 'Online status updated successfully',
    'users' => [
        'list' => [
            'success' => 'Users list retrieved successfully',
        ],
    ],
    'settings' => [
        'updated_successfully' => 'Chat settings updated successfully',
    ],
    'group' => [
        'create' => [
            'success' => 'Group created successfully',
            'failed' => 'Failed to create group'
        ],
        'update' => [
            'success' => 'Group updated successfully',
            'failed' => 'Failed to update group'
        ],
        'list' => [
            'success' => 'Group list retrieved successfully',
            'failed' => 'Failed to retrieve group list',
        ],
        'messages' => [
            'success' => 'Group messages retrieved successfully',
        ],
        'history' => [
            'success' => 'Group chat history retrieved successfully',
        ],
        'message' => [
            'success' => 'Group message sent successfully',
            'failed' => 'Failed to send group message'
        ],
        'member' => [
            'added' => 'Members added to group successfully',
        ],
        'members' => [
            'success' => 'Group members retrieved successfully',
        ],
        'available_users' => [
            'success' => 'Available users retrieved successfully',
        ],
        'room' => [
            'joined' => 'Successfully joined the group chat room',
            'left' => 'Successfully left the group chat room',
        ],
        'not_found' => 'Group not found or inactive',
        'not_member' => 'You are not a member of this group',
        'no_permission' => 'You do not have permission to perform this action',
        'no_members_added' => 'No members were added to the group',
        'members_removed_successfully' => ':count member(s) removed from group successfully',
        'members_partially_removed' => ':removed out of :total member(s) removed from group',
        'no_members_removed' => 'No members were removed from the group',
        'member_removed' => 'Member removed from group successfully',
        'remove_member_failed' => 'Failed to remove member from group',
        'permission_denied' => 'You do not have permission to perform this action',
        'member_role_updated_successfully' => 'Member role updated successfully',
        'member_role_update_failed' => 'Failed to update member role',
        'no_permission_send_messages' => 'You do not have permission to send messages in this group',
    ],
    'validation' => [
        'user_id' => [
            'required' => 'User ID is required.',
            'integer' => 'User ID must be an integer.',
            'exists' => 'The specified user does not exist.',
        ],
        'receiver_id' => [
            'required' => 'Receiver ID is required.',
            'integer' => 'Receiver ID must be an integer.',
            'exists' => 'The specified receiver does not exist.',
        ],
        'message' => [
            'required' => 'Message is required',
            'string' => 'Message must be a string',
            'max' => 'Message cannot exceed :max characters',
        ],
        'file' => [
            'required' => 'File is required',
            'invalid' => 'Invalid file format',
            'too_large' => 'File size exceeds maximum allowed size',
            'extension_not_allowed' => 'This file type is not allowed for security reasons',
            'mime_type_not_allowed' => 'This file type is not supported',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
        'group_id' => [
            'required' => 'Group ID is required',
            'integer' => 'Group ID must be an integer',
            'exists' => 'Group does not exist',
            'not_found' => 'Group not found',
            'inactive' => 'Group is not active',
            'not_member' => 'You are not a member of this group',
        ],
        'user_ids' => [
            'required' => 'User IDs are required',
            'array' => 'User IDs must be an array',
            'min' => 'At least one user ID is required',
            'item_required' => 'User ID is required',
            'item_integer' => 'User ID must be an integer',
            'duplicate' => 'Duplicate user IDs are not allowed',
            'not_found' => 'One or more users do not exist',
        ],
        'status' => [
            'required' => 'Status is required',
            'invalid' => 'Status must be online or offline',
        ],
        'search' => [
            'string' => 'Search must be a string',
            'max' => 'Search must not exceed 255 characters',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
            'max' => 'Limit must not exceed 100',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'chat_partner_id' => [
            'required' => 'Chat partner ID is required',
            'integer' => 'Chat partner ID must be an integer',
            'exists' => 'Chat partner does not exist',
        ],
        'custom_name' => [
            'string' => 'Custom name must be a string',
            'max' => 'Custom name cannot exceed 255 characters',
        ],
        'custom_avatar' => [
            'file' => 'Custom avatar must be a file',
            'image' => 'Custom avatar must be an image',
            'mimes' => 'Custom avatar must be jpeg, png, jpg, gif, or svg',
            'max' => 'Custom avatar cannot exceed 100MB',
        ],
        'is_pinned' => [
            'boolean' => 'Is pinned must be true or false',
        ],
        'is_hidden' => [
            'boolean' => 'Is hidden must be true or false',
            'in' => 'Is hidden must be true, false, 1, or 0',
        ],
        'message_search' => [
            'string' => 'Message search must be a string',
            'max' => 'Message search must not exceed 255 characters',
        ],
        'role' => [
            'required' => 'Role is required',
            'string' => 'Role must be a string',
            'invalid' => 'Role must be either admin or member',
        ],
        'chat_partner_ids' => [
            'required' => 'Chat partner IDs are required',
            'array' => 'Chat partner IDs must be an array',
            'min' => 'At least one chat partner ID is required',
            'item_required' => 'Chat partner ID is required',
            'item_integer' => 'Chat partner ID must be an integer',
            'duplicate' => 'Duplicate chat partner IDs are not allowed',
            'not_found' => 'One or more chat partners do not exist',
        ],
        'group_ids' => [
            'required' => 'Group IDs are required',
            'array' => 'Group IDs must be an array',
            'min' => 'At least one group ID is required',
            'item_required' => 'Group ID is required',
            'item_integer' => 'Group ID must be an integer',
            'duplicate' => 'Duplicate group IDs are not allowed',
            'not_found' => 'One or more groups do not exist',
        ],
        'invite_request' => [
            'already_pending' => 'An invite request for this user is already pending',
            'sent_successfully' => 'Invite request sent successfully',
            'not_found' => 'Invite request not found',
            'already_processed' => 'This invite request has already been processed',
            'approved' => 'Invite request approved successfully',
            'rejected' => 'Invite request rejected successfully',
        ],
        'user' => [
            'not_found' => 'User not found',
            'already_member' => 'User is already a group member',
            'not_member' => 'User is not a member of this group',
            'cannot_remove_owner' => 'Cannot remove the group owner',
            'no_permission_to_remove' => 'You do not have permission to remove this member',
            'cannot_mark_own_messages' => 'Cannot mark your own messages as read',
            'cannot_sort_self' => 'Cannot sort yourself in chat partners list',
            'cannot_change_owner_role' => 'Cannot change the role of the group owner',
            'owner_cannot_change_own_role' => 'The group owner cannot change their own role',
        ],
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer',
            'not_found' => 'User not found',
        ],
        'room_validation' => [
            'either_required' => 'Either receiver_id or group_id must be provided',
            'both_not_allowed' => 'Cannot provide both receiver_id and group_id',
        ],
        'group' => [
            'name' => [
                'required' => 'Group name is required',
                'string' => 'Group name must be a string',
                'max' => 'Group name cannot exceed 255 characters',
            ],
            'description' => [
                'string' => 'Group description must be a string',
                'max' => 'Group description cannot exceed 1000 characters',
            ],
            'avatar' => [
                'image' => 'Group avatar must be an image',
                'mimes' => 'Group avatar must be jpeg, jpg, png, or gif',
                'max' => 'Group avatar cannot exceed 100MB',
            ],
            'member_ids' => [
                'array' => 'Member IDs must be an array',
                'integer' => 'Member ID must be an integer',
                'exists' => 'Member does not exist',
            ],
            'not_member' => 'You are not a member of this group',
            'no_permission' => 'You do not have permission to perform this action',
            'no_permission_add_members' => 'You do not have permission to add members to this group',
            'no_permission_remove_members' => 'You do not have permission to remove members from this group',
            'only_owner_can_change_roles' => 'Only the group owner can change member roles',
        ],
    ],
    'attributes' => [
        'group_id' => 'Group',
        'group_ids' => 'Groups',
        'user_ids' => 'Users',
        'user_id' => 'User',
        'chat_partner' => 'Chat Partner',
        'chat_partners' => 'Chat Partners',
        'role' => 'Role',
    ],
    'group_pin_toggled_successfully' => 'Group pin status updated successfully',
    'group_pin_toggle_failed' => 'Failed to update group pin status',
    'group_hidden_toggled_successfully' => 'Group hidden status updated successfully',
    'group_hidden_toggle_failed' => 'Failed to update group hidden status',
    'group_sort_updated_successfully' => 'Group sort order updated successfully',
    'group_sort_update_failed' => 'Failed to update group sort order',
    'messages_marked_as_read_successfully' => 'Messages marked as read successfully',
    'group_messages_marked_as_read_successfully' => 'Group messages marked as read successfully',
    'pin' => [
        'toggled_successfully' => 'Chat pin status updated successfully',
        'toggle_failed' => 'Failed to update chat pin status',
    ],
    'hidden' => [
        'toggled_successfully' => 'Chat hidden status updated successfully',
        'toggle_failed' => 'Failed to update chat hidden status',
    ],
    'sort' => [
        'updated_successfully' => 'Chat sort order updated successfully',
        'update_failed' => 'Failed to update chat sort order',
    ],
    'typing' => [
        'started' => 'User started typing',
        'stopped' => 'User stopped typing',
    ],
];
