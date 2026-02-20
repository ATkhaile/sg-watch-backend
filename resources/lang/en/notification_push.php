<?php

return [
    'list' => [
        'message' => 'Successfully fetched notification list.',
    ],
    'create' => [
        'success' => 'Notification has been created successfully.',
        'failed'  => 'Failed to create notification.',
    ],
    'find' => [
        'message' => 'Successfully fetched notification detail.',
    ],
    'update' => [
        'success' => 'Notification has been updated successfully.',
        'failed'  => 'Failed to update notification.',
    ],
    'delete' => [
        'success' => 'Notification has been deleted successfully.',
        'failed'  => 'Failed to delete notification.',
    ],
    'update_receive_notification' => [
        'success' => 'Receive notification setting updated successfully.',
        'failed'  => 'Failed to update receive notification setting.',
    ],

    'validation' => [
        'title' => [
            'required' => 'Title is required.',
            'string'   => 'Title must be a string.',
            'max'      => 'Title may not be greater than :max characters.',
        ],
        'message' => [
            'required' => 'Message is required.',
            'string'   => 'Message must be a string.',
        ],
        'img_path' => [
            'mimes'    => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'file'     => 'Please select a valid image file.',
            'uploaded' => 'Failed to upload image.',
            'max'      => 'The image size may not be greater than :max KB.', // <-- thêm
        ],
        'all_user_flag' => [
            'required' => 'All user flag is required.',
            'boolean'  => 'All user flag must be true or false.',
        ],
        'push_now_flag' => [
            'required' => 'Send now flag is required.',
            'boolean'  => 'Send now flag must be true or false.',
        ],
        'push_schedule' => [
            'required_if' => 'Send date time is required when not sending immediately.',
            'date'        => 'Send date time is not a valid date.',
        ],
        'user_ids' => [
            'required_if' => 'Target users are required when not sending to all users.',
            'array'       => 'Target users must be an array.',
            '*.integer'   => 'User ID must be an integer.',
            '*.exists'    => 'The selected user does not exist.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer'  => 'ID must be an integer.',
            'exists'   => 'Notification does not exist.',
        ],
        'page' => [
            'integer' => 'Page must be an integer.',
            'min'     => 'Page must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer.',
            'min'     => 'Limit must be at least 1.',
        ],
        'sort' => [
            'string' => 'Sort field must be a string.',
        ],
        'direction' => [
            'in' => 'Sort direction must be ASC or DESC.',
        ],
        'search' => [
            'string' => 'Search keyword must be a string.',
            'max'    => 'Search keyword may not be greater than :max characters.',
        ],
        'remove_image' => [
            'boolean' => 'Remove image flag must be true or false.',
        ],
        'sound' => [
            'string' => 'Sound must be a string.',
            'in'     => 'Selected sound is invalid.',
        ],

        'redirect_type' => [
            'string' => 'Redirect type must be a string.',
            'in'     => 'Selected redirect type is invalid.',
        ],

        'app_page_id' => [
            'integer'     => 'App page ID must be an integer.',
            'exists'      => 'Selected app page does not exist.',
            'required_if' => 'App page is required when redirect type is "app_page".',
        ],

        'attach_file' => [
            'file'      => 'Attached file must be a valid file.',
            'mimes'     => 'Attached file must be a file of type: :values.',
            'mimetypes' => 'Attached file must be a file of type: :values.', // <-- thêm
            'max'       => 'Attached file size may not be greater than :max KB.',
        ],

        'attach_link' => [
            'string' => 'Web link must be a string.',
            'url'    => 'Web link must be a valid URL.',
            'max'    => 'Web link may not be greater than :max characters.',
        ],

        'remove_attach_file' => [
            'boolean' => 'Remove attached file flag must be true or false.', // <-- thêm
        ],
        'fcm_token' => [
            'required' => 'FCM token is required.',
            'string'   => 'FCM token must be a string.',
            'max'      => 'FCM token may not be greater than 255 characters.',
        ],
        'receive_notification_chat' => [
            'boolean'  => 'receive_notification_chat must be true or false.',
        ],
        'receive_reservation' => [
            'boolean'  => 'receive_reservation must be true or false.',
        ],
    ],
];
