<?php

namespace App\Domain\Chat\Factory;

use App\Http\Requests\Api\Chat\SendMessageRequest;

class SendMessageRequestFactory
{
    public function createFromRequest(SendMessageRequest $request): array
    {
        $params = [
            'user_id' => (int)auth()->user()->id,
            'receiver_id' => (int)$request->input('receiver_id'),
            'message' => $request->input('message'),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Add reply_to_message_id if present
        if ($request->has('reply_to_message_id') && $request->input('reply_to_message_id')) {
            $params['reply_to_message_id'] = (int)$request->input('reply_to_message_id');
        }

        // Add mentioned_user_ids if present
        if ($request->has('mentioned_user_ids') && is_array($request->input('mentioned_user_ids'))) {
            $params['mentioned_user_ids'] = array_map('intval', $request->input('mentioned_user_ids'));
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $params['message_type'] = 'file';
            // Keep message empty if no text was provided (file-only message)
            if (empty($params['message'])) {
                $params['message'] = '';
            }
            // Initialize file fields (will be filled by UseCase)
            $params['file_url'] = null;
            $params['file_name'] = null;
            $params['file_type'] = null;
            $params['file_size'] = null;
        } else {
            $params['message_type'] = 'text';
            // Set file fields to null for text messages
            $params['file_url'] = null;
            $params['file_name'] = null;
            $params['file_type'] = null;
            $params['file_size'] = null;
        }

        return $params;
    }
}
