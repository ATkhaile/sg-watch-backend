<?php
return [
    'validation' => [
        'page' => [
            'integer' => 'The page must be an integer.',
            'min' => 'The page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'The limit must be an integer.',
            'min' => 'The limit must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'The number of items per page must be an integer.',
            'min' => 'The number of items per page must be at least 1.',
            'max' => 'The number of items per page must not exceed 100.',
        ],
        'search' => [
            'string' => 'The search value must be a string.',
            'max' => 'The search value must not exceed 255 characters.',
        ],
        'sort' => [
            'string' => 'The sort field must be a string.',
            'in' => 'The selected sort field is invalid.',
        ],
        'direction' => [
            'in' => 'The direction must be ASC or DESC.',
        ],
        'folder_id' => [
            'required' => 'The folder ID is required.',
            'integer' => 'The folder ID must be an integer.',
            'exists' => 'The selected folder ID is invalid.',
        ],
        'id' => [
            'required' => 'The ID field is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
        "folder_name" => [
            'required' => 'A folder name is required.',
            'string' => 'The folder name must be a string.',
            'max' => 'The folder name must be a maximum of 255 characters.',
        ],
        'label' => [
            'required' => 'The label field is required.',
            'string' => 'The label must be a string.',
            'max' => 'The label may not be greater than 255 characters.',
        ],
        'description' => [
            'string' => 'The description must be a string.',
        ],
        'scenario_id' => [
            'required' => 'The scenario ID is required.',
            'integer' => 'The scenario ID must be an integer.',
            'exists' => 'The selected scenario ID is invalid.',
        ],
        'delay_type' => [
            'required' => 'The delay type is required.',
            'in' => 'The selected delay type is invalid.',
        ],
        'delay_sec' => [
            'integer' => 'The delay seconds must be an integer.',
            'min' => 'The delay seconds must be at least 0.',
            'required_if' => 'The delay seconds field is required when delay type is seconds.',
        ],
        'delay_days' => [
            'integer' => 'The delay days must be an integer.',
            'min' => 'The delay days must be at least 0.',
            'required_if' => 'The delay days field is required when delay type is days.',
        ],
        'delay_time' => [
            'date_format' => 'The delay time does not match the format H:i.',
            'required_if' => 'The delay time field is required when delay type is time.',
        ],
        'messages' => [
            'array' => 'The messages field must be an array.',
        ],
        'messages_patterns' => [
            'array' => 'The message patterns must be an array.',
            'min' => 'At least one message pattern is required.',
            'max' => 'The message patterns may not have more than :max items.',
        ],
        'messages_pattern_messages' => [
            'required' => 'The messages field is required.',
            'array' => 'The messages field must be an array.',
            'min' => 'At least one message is required.',
        ],
        'messages_pattern_message_type' => [
            'required' => 'The message type is required.',
            'string' => 'The message type must be a string.',
            'in' => 'The selected message type is invalid.',
        ],
        'messages_pattern_message_content' => [
            'required' => 'The message content is required.',
            'string' => 'The message content must be a string.',
            'max' => 'The message content may not exceed :max characters.',
        ],
        'step_ids' => [
            'required' => 'The step IDs field is required.',
            'array' => 'The step IDs must be an array.',
            'min' => 'The step IDs must have at least 1 item.',
        ],
        'step_ids.*' => [
            'required' => 'Each step ID is required.',
            'integer' => 'Each step ID must be an integer.',
            'exists' => 'One or more selected step IDs are invalid.',
        ],
        'target' => [
            'required' => 'Target is required.',
            'array' => 'Target must be an array.',

            'conditions' => [
                'type' => [
                    'string' => 'Condition type must be a string.',
                    'in' => 'Condition type is invalid.',
                ],
                'rules' => [
                    'array' => 'Rules must be an array.',
                ],
            ],

            'rules' => [
                'type' => [
                    'required' => 'Rule type is required.',
                    'string' => 'Rule type must be a string.',
                    'in' => 'Rule type is invalid.',
                ],
                'details' => [
                    'required' => 'Rule details are required.',
                    'array' => 'Rule details must be an array.',
                ],
                'condition' => [
                    'required' => 'Condition is required.',
                    'string' => 'Condition must be a string.',
                    'in' => 'Condition value is invalid.',
                ],
            ],
        ],

        'actions' => [
            'array' => 'Actions must be an array.',

            'operations' => [
                'required' => 'Operations are required.',
                'array' => 'Operations must be an array.',
                'min' => 'At least one operation is required.',
            ],

            'type' => [
                'required' => 'Operation type is required.',
                'in' => 'Operation type is invalid.',
            ],

            'details' => [
                'required' => 'Operation details are required.',
                'array' => 'Operation details must be an array.',
            ],
        ],
        'tag_id_required' => 'A tag_id is required.',
        'tag_id_not_allowed' => 'A tag_id is not allowed.',
        'tag_id_invalid' => 'The selected tag ID is invalid.',
        'user_id_required' => 'A user_id is required.',
        'user_id_not_allowed' => 'A user_id is not allowed.',
        'user_id_invalid' => 'The selected user ID is invalid.',
        'user_id' => [
            'required' => 'User ID is required.',
            'integer' => 'User ID must be an integer.',
            'exists' => 'The selected User ID is invalid.',
        ],
        'scenario_step_id' => [
            'required' => 'Scenario Step ID is required.',
            'integer' => 'Scenario Step ID must be an integer.',
            'exists' => 'The selected Scenario Step ID is invalid.',
        ],
        'entry_at' => [
            'date_format' => 'The entry_at does not match the format Y/m/d H:i:s.',
        ],
    ],
    'list' => [
        'folder' => [
            'message' => 'Scenario folder list.',
        ],
        "scenario" => [
            'message' => 'Scenario List',
        ],
        'step' => [
            'message' => 'Scenario Step List',
        ],
        'user_step' => [
            'message' => 'User Step List',
            'success' => 'User step list retrieved successfully.',
        ],
    ],
    'find' => [
        'folder' => [
            'message' => 'Scenario folder details.',
        ],
        'scenario' => [
            'message' => 'Scenario details.',
        ],
        'step' => [
            'message' => 'Scenario step details.',
        ],
        'user_step' => [
            'message' => 'User step details.',
            'success' => 'User step details retrieved successfully.',
        ],
    ],
    'create' => [
        'folder' => [
            'success' => 'The scenario folder was created successfully.',
            'failed' => 'Failed to create the scenario folder.',
        ],
        'scenario' => [
            'success' => 'The scenario was created successfully.',
            'failed' => 'Failed to create the scenario.',
        ],
        'step' => [
            'success' => 'The scenario step was created successfully.',
            'failed' => 'Failed to create the scenario step.',
        ],
    ],
    'update' => [
        'folder' => [
            'success' => 'The scenario folder was updated successfully.',
            'failed' => 'Failed to update the scenario folder.',
        ],
        'scenario' => [
            'success' => 'The scenario was updated successfully.',
            'failed' => 'Failed to update the scenario.',
        ],
        'step' => [
            'success' => 'The scenario step was updated successfully.',
            'failed' => 'Failed to update the scenario step.',
        ],
    ],
    'delete' => [
        'folder' => [
            'success' => 'The scenario folder was deleted successfully.',
            'failed' => 'Failed to delete the scenario folder.',
        ],
        'scenario' => [
            'success' => 'The scenario was deleted successfully.',
            'failed' => 'Failed to delete the scenario.',
        ],
        'step' => [
            'success' => 'The scenario step was deleted successfully.',
            'failed' => 'Failed to delete the scenario step.',
        ],
        'user_step' => [
            'success' => 'The user step was deleted successfully.',
            'failed' => 'Failed to delete the user step.',
        ],
    ],
    'reorder' => [
        'steps' => [
            'success' => 'The scenario steps were reordered successfully.',
            'failed' => 'Failed to reorder the scenario steps.',
        ],
    ],
    'action' => [
        'user_step' => [
            'success' => 'User step action successfully.',
            'failed' => 'User step action failed.',
        ],
    ],
    'crontab' => [
        'success' => 'Scenario crontab processed successfully.',
        'failed' => 'Scenario crontab processed failed.',
    ],
];
