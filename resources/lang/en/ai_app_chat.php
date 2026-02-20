<?php

return [
    'application_not_found' => 'AI application not found.',
    'provider_inactive' => 'AI provider is inactive.',
    'send_success' => 'Message sent successfully.',
    'conversation_not_found' => 'Conversation not found.',
    'conversation_name_updated' => 'Conversation name updated successfully.',
    'conversation_shared' => 'Conversation shared successfully.',
    'conversation_unshared' => 'Conversation unshared successfully.',
    'shared_conversation_not_found' => 'Shared conversation not found or no longer shared.',

    // Reasoning steps titles
    'reasoning_steps' => [
        'analyzing_request' => 'Analyzing request',
        'generating_final_response' => 'Generating final response',
        'processing' => 'Processing',
        'received_tool_results' => 'Received tool results',
        'completed_tool_executions' => 'Completed :success/:total tool executions',

        // Tool call titles (when calling)
        'tool_call' => [
            'search_knowledge' => 'Searching knowledge base',
            'generate_image' => 'Generating image',
            'generate_website' => 'Generating website',
            'fetch_url_content' => 'Fetching URL content',
            'get_current_user' => 'Getting user information',
            'code_interpreter' => 'Executing code',
            'web_search' => 'Searching the web',
            'default' => 'Calling :tool_name',
        ],

        // Tool result titles (when completed)
        'tool_result' => [
            'search_knowledge' => 'Retrieved knowledge results',
            'generate_image' => 'Image generated',
            'generate_website' => 'Website code generated',
            'fetch_url_content' => 'URL content fetched',
            'get_current_user' => 'User info retrieved',
            'code_interpreter' => 'Code executed',
            'web_search' => 'Web search completed',
            'default' => ':tool_name completed',
        ],
    ],

    'validation' => [
        'message' => [
            'required' => 'Message is required.',
            'string' => 'Message must be a string.',
            'max' => 'Message must not exceed 10000 characters.',
        ],
        'conversation_id' => [
            'integer' => 'Conversation ID must be an integer.',
            'exists' => 'The specified conversation does not exist.',
        ],
        'from_source' => [
            'string' => 'Source must be a string.',
            'max' => 'Source must not exceed 50 characters.',
        ],
        'files' => [
            'array' => 'Files must be an array.',
            'max' => 'You can upload up to 5 files.',
        ],
        'files_item' => [
            'file' => 'Each item must be a valid file.',
            'mimes' => 'File type must be: jpg, jpeg, png, gif, webp, pdf, doc, docx, xls, xlsx, ppt, pptx.',
            'max' => 'Each file must not exceed 20MB.',
        ],
    ],
];
