<?php

namespace App\Services\AiServices\FunctionCalling\Converters;

use App\Services\AiServices\FunctionCalling\ToolCall;
use App\Services\AiServices\FunctionCalling\ToolResult;
use App\Services\AiServices\FunctionCalling\Tools\AbstractTool;
use Illuminate\Support\Collection;

class GeminiToolConverter extends AbstractToolConverter
{
    /**
     * Convert tools to Gemini format
     */
    public function convertToolDefinitions(Collection $tools): array
    {
        return [
            [
                'function_declarations' => $tools->map(function (AbstractTool $tool) {
                    return [
                        'name' => $tool->getName(),
                        'description' => $tool->getDescription(),
                        'parameters' => $this->convertToGeminiSchema($tool->getParametersSchema()),
                    ];
                })->values()->toArray(),
            ],
        ];
    }

    /**
     * Convert JSON Schema to Gemini's schema format
     */
    private function convertToGeminiSchema(array $schema): array
    {
        $geminiSchema = [
            'type' => strtoupper($schema['type'] ?? 'OBJECT'),
            'properties' => $this->convertProperties($schema['properties'] ?? []),
        ];

        if (!empty($schema['required'])) {
            $geminiSchema['required'] = $schema['required'];
        }

        return $geminiSchema;
    }

    /**
     * Convert properties to Gemini format
     */
    private function convertProperties(array $properties): array
    {
        $converted = [];
        foreach ($properties as $name => $prop) {
            $converted[$name] = [
                'type' => strtoupper($prop['type'] ?? 'STRING'),
            ];

            if (!empty($prop['description'])) {
                $converted[$name]['description'] = $prop['description'];
            }

            if (isset($prop['enum'])) {
                $converted[$name]['enum'] = $prop['enum'];
            }

            // Handle array type
            if (strtoupper($prop['type'] ?? '') === 'ARRAY' && isset($prop['items'])) {
                $converted[$name]['items'] = [
                    'type' => strtoupper($prop['items']['type'] ?? 'STRING'),
                ];
            }
        }
        return $converted;
    }

    /**
     * Parse tool calls from Gemini response
     */
    public function parseToolCalls(array $response): array
    {
        $toolCalls = [];

        $candidates = $response['candidates'] ?? [];
        foreach ($candidates as $candidate) {
            $parts = $candidate['content']['parts'] ?? [];
            foreach ($parts as $part) {
                if (isset($part['functionCall'])) {
                    $toolCalls[] = new ToolCall(
                        id: uniqid('gemini_call_'),
                        name: $part['functionCall']['name'],
                        arguments: $part['functionCall']['args'] ?? []
                    );
                }
            }
        }

        return $toolCalls;
    }

    /**
     * Build tool results message for Gemini
     */
    public function buildToolResultsMessage(array $toolCalls, array $results, ?array $rawResponse = null): array
    {
        $parts = [];

        foreach ($toolCalls as $index => $toolCall) {
            $result = $results[$index] ?? null;

            $responseData = ['error' => 'Tool not found'];
            if ($result instanceof ToolResult) {
                $responseData = $result->success
                    ? ['result' => $result->data, 'message' => $result->message]
                    : ['error' => $result->message];
            }

            $parts[] = [
                'functionResponse' => [
                    'name' => $toolCall->name,
                    'response' => $responseData,
                ],
            ];
        }

        return [
            [
                'role' => 'function',
                'parts' => $parts,
            ],
        ];
    }

    /**
     * Check if response contains tool calls
     */
    public function hasToolCalls(array $response): bool
    {
        $candidates = $response['candidates'] ?? [];
        foreach ($candidates as $candidate) {
            $parts = $candidate['content']['parts'] ?? [];
            foreach ($parts as $part) {
                if (isset($part['functionCall'])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if response is complete
     */
    public function isComplete(array $response): bool
    {
        $candidates = $response['candidates'] ?? [];
        foreach ($candidates as $candidate) {
            if (($candidate['finishReason'] ?? '') === 'STOP') {
                return true;
            }
        }
        return !$this->hasToolCalls($response);
    }

    /**
     * Extract text content from Gemini response
     */
    public function extractTextContent(array $response): string
    {
        $text = '';

        $candidates = $response['candidates'] ?? [];
        foreach ($candidates as $candidate) {
            $parts = $candidate['content']['parts'] ?? [];
            foreach ($parts as $part) {
                if (isset($part['text'])) {
                    $text .= $part['text'];
                }
            }
        }

        return $text;
    }
}
