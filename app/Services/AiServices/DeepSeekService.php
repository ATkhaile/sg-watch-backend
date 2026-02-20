<?php

namespace App\Services\AiServices;

use App\Services\AiServices\Traits\HttpClientTrait;

class DeepSeekService
{
    use HttpClientTrait;

    public function chat(array $messages, array $config): array
    {
        $baseUrl = $config['baseUrl'] ?? 'https://api.deepseek.com';
        $url = rtrim($baseUrl, '/') . '/chat/completions';

        $query = [
            'model' => $config['model'],
            'messages' => $messages,
            'temperature' => $config['temperature'] ?? 0.7,
            'max_tokens' => $config['maxTokens'] ?? 2000,
        ];

        // Add tools if provided (for function calling)
        if (!empty($config['tools'])) {
            $query['tools'] = $config['tools'];
        }

        $response = $this->httpPost($url, $query, [
            'Authorization' => 'Bearer ' . $config['apiKey'],
        ], 120);

        if ($response === false) {
            throw new \Exception('DeepSeek API error: Failed to connect');
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('DeepSeek API error: ' . ($data['error']['message'] ?? $response));
        }

        return [
            'answer' => $data['choices'][0]['message']['content'] ?? '',
            'query' => $query,
            'message_tokens' => $data['usage']['prompt_tokens'] ?? 0,
            'answer_tokens' => $data['usage']['completion_tokens'] ?? 0,
            'generated_files' => [],
            'metadata' => [
                'model' => $data['model'] ?? $config['model'],
                'finish_reason' => $data['choices'][0]['finish_reason'] ?? null,
            ],
            'raw_response' => $data, // Include raw response for function calling
        ];
    }

    /**
     * Chat with raw response for function calling support
     */
    public function chatWithFunctionCalling(array $messages, array $config): array
    {
        $baseUrl = $config['baseUrl'] ?? 'https://api.deepseek.com';
        $url = rtrim($baseUrl, '/') . '/chat/completions';

        // Convert tool results to assistant message format for DeepSeek
        $formattedMessages = [];
        foreach ($messages as $msg) {
            if (isset($msg['type']) && $msg['type'] === 'function_call_output') {
                // DeepSeek uses OpenAI format for tool responses
                $formattedMessages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $msg['call_id'],
                    'content' => $msg['output'],
                ];
            } elseif (isset($msg['role'])) {
                // Only add messages that have a role
                $formattedMessages[] = $msg;
            }
        }

        $query = [
            'model' => $config['model'],
            'messages' => $formattedMessages,
            'temperature' => $config['temperature'] ?? 0.7,
            'max_tokens' => $config['maxTokens'] ?? 2000,
        ];

        if (!empty($config['tools'])) {
            $query['tools'] = $config['tools'];
        }

        $response = $this->httpPost($url, $query, [
            'Authorization' => 'Bearer ' . $config['apiKey'],
        ], 300);

        if ($response === false) {
            throw new \Exception('DeepSeek API error: Failed to connect');
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('DeepSeek API error: ' . ($data['error']['message'] ?? $response));
        }

        return [
            'raw_response' => $data,
            'query' => $query,
            'message_tokens' => $data['usage']['prompt_tokens'] ?? 0,
            'answer_tokens' => $data['usage']['completion_tokens'] ?? 0,
        ];
    }

    public function getModels(string $apiKey, ?string $baseUrl = null): array
    {
        $url = $baseUrl ? rtrim($baseUrl, '/') . '/models' : 'https://api.deepseek.com/models';

        $response = $this->httpGet($url, ['Authorization' => 'Bearer ' . $apiKey]);

        if ($response === false) {
            throw new \Exception('Failed to fetch DeepSeek models');
        }

        $data = json_decode($response, true);

        return collect($data['data'] ?? [])
            ->map(fn($model) => [
                'id' => $model['id'],
                'name' => $model['id'],
            ])
            ->sortBy('id')
            ->values()
            ->toArray();
    }
}
