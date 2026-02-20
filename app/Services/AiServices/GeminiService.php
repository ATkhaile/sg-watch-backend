<?php

namespace App\Services\AiServices;

use App\Services\AiServices\Traits\HttpClientTrait;
use App\Services\OfficeFileExtractor;

class GeminiService
{
    use HttpClientTrait;

    public function chat(array $messages, array $config): array
    {
        $model = $config['model'];
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $config['apiKey'];

        $contents = [];
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                continue;
            }
            $parts = $this->buildParts($msg['content'], $msg['files'] ?? null);
            $contents[] = [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => $parts,
            ];
        }

        $systemInstruction = null;
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemInstruction = ['parts' => [['text' => $msg['content']]]];
                break;
            }
        }

        $query = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $config['temperature'] ?? 0.7,
                'maxOutputTokens' => $config['maxTokens'] ?? 2000,
            ],
        ];

        if ($systemInstruction) {
            $query['systemInstruction'] = $systemInstruction;
        }

        // Add tools if provided (for function calling)
        if (!empty($config['tools'])) {
            $query['tools'] = $config['tools'];
        }

        $response = $this->httpPost($url, $query, [], 120);

        if ($response === false) {
            throw new \Exception('Gemini API error: Failed to connect');
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('Gemini API error: ' . ($data['error']['message'] ?? $response));
        }

        $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return [
            'answer' => $answer,
            'query' => $query,
            'message_tokens' => $data['usageMetadata']['promptTokenCount'] ?? 0,
            'answer_tokens' => $data['usageMetadata']['candidatesTokenCount'] ?? 0,
            'generated_files' => [],
            'metadata' => [
                'model' => $model,
                'finish_reason' => $data['candidates'][0]['finishReason'] ?? null,
            ],
            'raw_response' => $data, // Include raw response for function calling
        ];
    }

    /**
     * Chat with raw response for function calling support
     */
    public function chatWithFunctionCalling(array $messages, array $config): array
    {
        $model = $config['model'];
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $config['apiKey'];

        $contents = [];
        $systemInstruction = null;

        foreach ($messages as $msg) {
            $role = $msg['role'] ?? null;

            // Extract system instruction
            if ($role === 'system') {
                $systemInstruction = ['parts' => [['text' => $msg['content']]]];
                continue;
            }

            // Handle function response messages
            if ($role === 'function' && isset($msg['parts'])) {
                $contents[] = $msg;
                continue;
            }

            // Skip messages without role (e.g., OpenAI function_call_output format)
            if ($role === null) {
                continue;
            }

            $parts = $this->buildParts($msg['content'] ?? '', $msg['files'] ?? null);
            $contents[] = [
                'role' => $role === 'assistant' ? 'model' : 'user',
                'parts' => $parts,
            ];
        }

        $query = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $config['temperature'] ?? 0.7,
                'maxOutputTokens' => $config['maxTokens'] ?? 2000,
            ],
        ];

        if ($systemInstruction) {
            $query['systemInstruction'] = $systemInstruction;
        }

        if (!empty($config['tools'])) {
            $query['tools'] = $config['tools'];
        }

        $response = $this->httpPost($url, $query, [], 300);

        if ($response === false) {
            throw new \Exception('Gemini API error: Failed to connect');
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('Gemini API error: ' . ($data['error']['message'] ?? $response));
        }

        return [
            'raw_response' => $data,
            'query' => $query,
            'message_tokens' => $data['usageMetadata']['promptTokenCount'] ?? 0,
            'answer_tokens' => $data['usageMetadata']['candidatesTokenCount'] ?? 0,
        ];
    }

    public function getModels(string $apiKey, ?string $baseUrl = null): array
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey;

        $response = $this->httpGet($url);

        if ($response === false) {
            throw new \Exception('Failed to fetch Gemini models');
        }

        $data = json_decode($response, true);

        return collect($data['models'] ?? [])
            ->filter(fn($model) => str_contains($model['name'], 'gemini'))
            ->map(fn($model) => [
                'id' => str_replace('models/', '', $model['name']),
                'name' => $model['displayName'] ?? str_replace('models/', '', $model['name']),
            ])
            ->sortBy('id')
            ->values()
            ->toArray();
    }

    private function buildParts(string $text, ?array $files): array
    {
        $parts = [];
        $extractedTexts = [];
        $extractor = new OfficeFileExtractor();

        if (!empty($files)) {
            foreach ($files as $file) {
                $mimeType = $file['mime_type'] ?? 'application/octet-stream';
                $publicUrl = $file['url'] ?? null;
                $storagePath = $file['storage_path'] ?? null;

                if (str_starts_with($mimeType, 'image/')) {
                    if ($publicUrl) {
                        $parts[] = [
                            'file_data' => [
                                'mime_type' => $mimeType,
                                'file_uri' => $publicUrl,
                            ],
                        ];
                    }
                } elseif ($mimeType === 'application/pdf') {
                    if ($publicUrl) {
                        $parts[] = [
                            'file_data' => [
                                'mime_type' => $mimeType,
                                'file_uri' => $publicUrl,
                            ],
                        ];
                    }
                } elseif ($extractor->needsExtraction($mimeType)) {
                    if ($storagePath) {
                        $fullPath = storage_path('app/public/' . $storagePath);
                        $extractedText = $extractor->extractText($fullPath, $mimeType);
                        if ($extractedText) {
                            $fileName = $file['name'] ?? 'document';
                            $extractedTexts[] = "--- Content from file: {$fileName} ---\n{$extractedText}\n--- End of file ---";
                        }
                    }
                }
            }
        }

        $finalText = $text;
        if (!empty($extractedTexts)) {
            $finalText = implode("\n\n", $extractedTexts) . "\n\n" . $text;
        }

        $parts[] = ['text' => $finalText];

        return $parts;
    }
}
