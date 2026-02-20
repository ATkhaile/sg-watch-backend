<?php

namespace App\Services\AiServices\FunctionCalling\Converters;

use App\Services\AiServices\FunctionCalling\ToolCall;
use App\Services\AiServices\FunctionCalling\Tools\AbstractTool;
use Illuminate\Support\Collection;

class OpenAiToolConverter extends AbstractToolConverter
{
    /**
     * Convert tools to OpenAI format
     */
    public function convertToolDefinitions(Collection $tools): array
    {
        return $tools->map(function (AbstractTool $tool) {
            return [
                'type' => 'function',
                'name' => $tool->getName(),
                'description' => $tool->getDescription(),
                'parameters' => $tool->getParametersSchema(),
            ];
        })->values()->toArray();
    }

    /**
     * Parse tool calls from OpenAI response
     */
    public function parseToolCalls(array $response): array
    {
        $toolCalls = [];

        // Handle Responses API format (newer format used by OpenAI)
        if (isset($response['output'])) {
            foreach ($response['output'] as $output) {
                if ($output['type'] === 'function_call') {
                    $toolCalls[] = new ToolCall(
                        id: $output['call_id'] ?? uniqid('call_'),
                        name: $output['name'],
                        arguments: json_decode($output['arguments'] ?? '{}', true) ?? []
                    );
                }
            }
            return $toolCalls;
        }

        // Handle Chat Completions API format (older format)
        $choices = $response['choices'] ?? [];
        foreach ($choices as $choice) {
            $message = $choice['message'] ?? [];
            if (isset($message['tool_calls'])) {
                foreach ($message['tool_calls'] as $call) {
                    if ($call['type'] === 'function') {
                        $toolCalls[] = new ToolCall(
                            id: $call['id'],
                            name: $call['function']['name'],
                            arguments: json_decode($call['function']['arguments'] ?? '{}', true) ?? []
                        );
                    }
                }
            }
        }

        return $toolCalls;
    }

    /**
     * Build tool results message for follow-up request
     * OpenAI Responses API requires function_call items to be included before function_call_output
     */
    public function buildToolResultsMessage(array $toolCalls, array $results, ?array $rawResponse = null): array
    {
        $messages = [];

        // First, add the function_call items from the original response
        // OpenAI requires these to be present when sending function_call_output
        if ($rawResponse && isset($rawResponse['output'])) {
            foreach ($rawResponse['output'] as $output) {
                if ($output['type'] === 'function_call') {
                    $messages[] = $output;
                }
            }
        }

        // Then add the function_call_output items
        foreach ($toolCalls as $index => $toolCall) {
            $result = $results[$index] ?? null;
            $messages[] = [
                'type' => 'function_call_output',
                'call_id' => $toolCall->id,
                'output' => $result ? $result->toAiResponse() : json_encode(['error' => 'Tool not found']),
            ];
        }

        return $messages;
    }

    /**
     * Check if response contains tool calls that need execution
     * Note: web_search_call and image_generation_call are auto-handled by OpenAI and don't need execution
     */
    public function hasToolCalls(array $response): bool
    {
        // Check Responses API format
        if (isset($response['output'])) {
            foreach ($response['output'] as $output) {
                // Only return true for function_call (custom tools that need execution)
                // web_search_call and image_generation_call are auto-handled by OpenAI - no action needed
                if ($output['type'] === 'function_call') {
                    return true;
                }
            }
        }

        // Check Chat Completions format
        $choices = $response['choices'] ?? [];
        foreach ($choices as $choice) {
            if (!empty($choice['message']['tool_calls'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract generated images from Responses API output
     *
     * @param array $response Raw API response
     * @return array Array of generated image data
     */
    public function extractGeneratedImages(array $response): array
    {
        $images = [];

        if (!isset($response['output'])) {
            return $images;
        }

        foreach ($response['output'] as $index => $output) {
            if ($output['type'] === 'image_generation_call') {
                $imageBase64 = $output['result'] ?? null;
                if ($imageBase64) {
                    $images[] = [
                        'type' => 'image',
                        'data' => base64_decode($imageBase64),
                        'mime_type' => 'image/png',
                        'original_name' => 'generated_image_' . date('YmdHis') . '_' . ($index + 1) . '.png',
                    ];
                }
            }
        }

        return $images;
    }

    /**
     * Check if response contains image generation results
     */
    public function hasImageGenerationResults(array $response): bool
    {
        if (!isset($response['output'])) {
            return false;
        }

        foreach ($response['output'] as $output) {
            if ($output['type'] === 'image_generation_call') {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if response is complete (no more tool calls)
     */
    public function isComplete(array $response): bool
    {
        // Check Responses API format
        if (isset($response['status'])) {
            return $response['status'] === 'completed' && !$this->hasToolCalls($response);
        }

        // Check Chat Completions format
        $choices = $response['choices'] ?? [];
        foreach ($choices as $choice) {
            if (($choice['finish_reason'] ?? '') === 'stop') {
                return true;
            }
        }

        return !$this->hasToolCalls($response);
    }

    /**
     * Extract code interpreter generated files from Responses API output
     * Handles output_file, output_image, and container files
     *
     * @param array $response Raw API response
     * @return array Array of generated file data
     */
    public function extractCodeInterpreterFiles(array $response): array
    {
        $files = [];

        if (!isset($response['output'])) {
            return $files;
        }

        // Get container_id from response if available
        $containerId = $response['metadata']['container_id'] ?? null;

        foreach ($response['output'] as $index => $output) {
            if ($output['type'] === 'code_interpreter_call') {
                // Log the structure to understand OpenAI response format
                \Illuminate\Support\Facades\Log::info('Code interpreter output structure', [
                    'index' => $index,
                    'output_keys' => array_keys($output),
                    'has_results' => isset($output['results']),
                    'has_outputs' => isset($output['outputs']),
                    'results_count' => isset($output['results']) ? count($output['results']) : 0,
                    'outputs_count' => isset($output['outputs']) ? count($output['outputs']) : 0,
                    'container_id' => $output['container_id'] ?? $containerId,
                ]);

                // Get container_id from output if not in response metadata
                $outputContainerId = $output['container_id'] ?? $containerId;

                // Try 'results' field first (standard format)
                $results = $output['results'] ?? [];

                // If no results, try 'outputs' field (alternative format with include parameter)
                if (empty($results) && isset($output['outputs'])) {
                    $results = $output['outputs'];
                }

                foreach ($results as $resultIndex => $result) {
                    $resultType = $result['type'] ?? null;

                    \Illuminate\Support\Facades\Log::info('Code interpreter result item', [
                        'result_index' => $resultIndex,
                        'result_type' => $resultType,
                        'result_keys' => array_keys($result),
                    ]);

                    // Handle files type
                    if ($resultType === 'files') {
                        foreach ($result['files'] ?? [] as $fileIndex => $file) {
                            $fileData = $this->processCodeInterpreterFile($file, $index, $fileIndex);
                            if ($fileData) {
                                $files[] = $fileData;
                            }
                        }
                    }

                    // Handle images type (e.g., matplotlib plots)
                    elseif ($resultType === 'images') {
                        foreach ($result['images'] ?? [] as $imageIndex => $image) {
                            $imageData = $this->processCodeInterpreterImage($image, $index, $imageIndex);
                            if ($imageData) {
                                $files[] = $imageData;
                            }
                        }
                    }

                    // Handle single file output (alternative format)
                    elseif ($resultType === 'file') {
                        $fileData = $this->processCodeInterpreterFile($result, $index, $resultIndex);
                        if ($fileData) {
                            $files[] = $fileData;
                        }
                    }

                    // Handle single image output (alternative format)
                    elseif ($resultType === 'image') {
                        $imageData = $this->processCodeInterpreterImage($result, $index, $resultIndex);
                        if ($imageData) {
                            $files[] = $imageData;
                        }
                    }

                    // Handle container_file_citation type (file saved by code interpreter)
                    elseif ($resultType === 'container_file_citation' || isset($result['file_id'])) {
                        $fileContainerId = $result['container_id'] ?? $outputContainerId;
                        \Illuminate\Support\Facades\Log::info('Container file citation found', [
                            'file_id' => $result['file_id'] ?? null,
                            'container_id' => $fileContainerId,
                            'filename' => $result['filename'] ?? null,
                        ]);

                        // Add file reference for later download
                        if (isset($result['file_id']) && $fileContainerId) {
                            $files[] = [
                                'type' => 'container_file',
                                'container_id' => $fileContainerId,
                                'file_id' => $result['file_id'],
                                'filename' => $result['filename'] ?? $result['file_id'],
                                'source' => 'code_interpreter',
                                'needs_download' => true,
                            ];
                        }
                    }
                }
            }

            // Also check for message outputs with annotations (container_file_citation)
            if ($output['type'] === 'message') {
                foreach ($output['content'] ?? [] as $content) {
                    if ($content['type'] === 'output_text' && isset($content['annotations'])) {
                        foreach ($content['annotations'] as $annotation) {
                            if ($annotation['type'] === 'container_file_citation') {
                                $fileContainerId = $annotation['container_id'] ?? $containerId;
                                \Illuminate\Support\Facades\Log::info('Container file citation in message annotation', [
                                    'file_id' => $annotation['file_id'] ?? null,
                                    'container_id' => $fileContainerId,
                                    'filename' => $annotation['filename'] ?? null,
                                ]);

                                if (isset($annotation['file_id']) && $fileContainerId) {
                                    $files[] = [
                                        'type' => 'container_file',
                                        'container_id' => $fileContainerId,
                                        'file_id' => $annotation['file_id'],
                                        'filename' => $annotation['filename'] ?? $annotation['file_id'],
                                        'source' => 'code_interpreter',
                                        'needs_download' => true,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Process a code interpreter file from the API response
     */
    private function processCodeInterpreterFile(array $file, int $outputIndex, int $fileIndex): ?array
    {
        $fileData = null;
        $filename = $file['name'] ?? $file['filename'] ?? ('code_interpreter_file_' . date('YmdHis') . '_' . ($outputIndex + 1) . '_' . ($fileIndex + 1));
        $mimeType = $file['mime_type'] ?? $file['content_type'] ?? 'application/octet-stream';

        // Handle base64 encoded file data (various field names)
        if (!empty($file['file_data'])) {
            $fileData = base64_decode($file['file_data']);
        } elseif (!empty($file['content'])) {
            // Check if content is base64 or plain text
            $decoded = base64_decode($file['content'], true);
            $fileData = $decoded !== false ? $decoded : $file['content'];
        } elseif (!empty($file['data'])) {
            // Check if data is base64 or plain text
            $decoded = base64_decode($file['data'], true);
            $fileData = $decoded !== false ? $decoded : $file['data'];
        } elseif (!empty($file['url'])) {
            // Download file from URL if provided
            $fileData = $this->downloadFileFromUrl($file['url']);
        } elseif (!empty($file['file_url'])) {
            // Alternative field name for URL
            $fileData = $this->downloadFileFromUrl($file['file_url']);
        }

        if (!$fileData) {
            return null;
        }

        return [
            'type' => 'file',
            'data' => $fileData,
            'mime_type' => $mimeType,
            'original_name' => $filename,
            'source' => 'code_interpreter',
        ];
    }

    /**
     * Process a code interpreter generated image (e.g., matplotlib plot)
     */
    private function processCodeInterpreterImage(array $image, int $outputIndex, int $imageIndex): ?array
    {
        $imageData = null;
        $mimeType = $image['mime_type'] ?? $image['content_type'] ?? 'image/png';
        $filename = $image['name'] ?? $image['filename'] ?? ('code_interpreter_plot_' . date('YmdHis') . '_' . ($outputIndex + 1) . '_' . ($imageIndex + 1) . '.png');

        // Handle base64 encoded image data (various field names)
        if (!empty($image['image_data'])) {
            $imageData = base64_decode($image['image_data']);
        } elseif (!empty($image['b64_image'])) {
            $imageData = base64_decode($image['b64_image']);
        } elseif (!empty($image['data'])) {
            $imageData = base64_decode($image['data']);
        } elseif (!empty($image['content'])) {
            $imageData = base64_decode($image['content']);
        } elseif (!empty($image['url'])) {
            $imageData = $this->downloadFileFromUrl($image['url']);
        } elseif (!empty($image['image_url'])) {
            $imageData = $this->downloadFileFromUrl($image['image_url']);
        }

        if (!$imageData) {
            return null;
        }

        return [
            'type' => 'image',
            'data' => $imageData,
            'mime_type' => $mimeType,
            'original_name' => $filename,
            'source' => 'code_interpreter',
        ];
    }

    /**
     * Download file from URL
     */
    private function downloadFileFromUrl(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 60,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $data = @file_get_contents($url, false, $context);
        return $data !== false ? $data : null;
    }

    /**
     * Check if response contains code interpreter results
     */
    public function hasCodeInterpreterResults(array $response): bool
    {
        if (!isset($response['output'])) {
            return false;
        }

        foreach ($response['output'] as $output) {
            if ($output['type'] === 'code_interpreter_call') {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract all tool calls from response including built-in tools
     * This includes function_call, code_interpreter_call, image_generation_call, web_search_call
     * Used for displaying in reasoning steps UI
     *
     * @param array $response Raw API response
     * @return array Array of tool call info with type and details
     */
    public function extractAllToolCalls(array $response): array
    {
        $toolCalls = [];

        if (!isset($response['output'])) {
            return $toolCalls;
        }

        // Check if the overall response is completed
        // When response status is 'completed', all tool calls have finished
        $responseCompleted = ($response['status'] ?? '') === 'completed';

        foreach ($response['output'] as $output) {
            $type = $output['type'] ?? null;

            switch ($type) {
                case 'function_call':
                    $toolCalls[] = [
                        'type' => 'function_call',
                        'name' => $output['name'] ?? 'unknown',
                        'call_id' => $output['call_id'] ?? null,
                        'arguments' => json_decode($output['arguments'] ?? '{}', true) ?? [],
                    ];
                    break;

                case 'code_interpreter_call':
                    // Check for results in various possible locations
                    // If response is completed, all code interpreter calls have finished
                    $hasResults = $responseCompleted
                        || !empty($output['results'])
                        || !empty($output['outputs'])
                        || ($output['status'] ?? '') === 'completed';
                    $toolCalls[] = [
                        'type' => 'code_interpreter',
                        'name' => 'code_interpreter',
                        'id' => $output['id'] ?? null,
                        'code' => $output['code'] ?? null,
                        'has_results' => $hasResults,
                    ];
                    break;

                case 'image_generation_call':
                    $toolCalls[] = [
                        'type' => 'image_generation',
                        'name' => 'generate_image',
                        'prompt' => $output['prompt'] ?? null,
                        'has_result' => $responseCompleted || !empty($output['result']),
                    ];
                    break;

                case 'web_search_call':
                    $toolCalls[] = [
                        'type' => 'web_search',
                        'name' => 'web_search',
                        'id' => $output['id'] ?? null,
                        'status' => $responseCompleted ? 'completed' : ($output['status'] ?? null),
                    ];
                    break;
            }
        }

        return $toolCalls;
    }

    /**
     * Extract text content from response
     */
    public function extractTextContent(array $response): string
    {
        $text = '';

        // Handle Responses API format
        if (isset($response['output'])) {
            foreach ($response['output'] as $output) {
                if ($output['type'] === 'message') {
                    foreach ($output['content'] ?? [] as $content) {
                        if ($content['type'] === 'output_text') {
                            $text .= $content['text'];
                        }
                    }
                }
            }
            return $text;
        }

        // Handle Chat Completions format
        $choices = $response['choices'] ?? [];
        foreach ($choices as $choice) {
            $message = $choice['message'] ?? [];
            if (isset($message['content'])) {
                $text .= $message['content'];
            }
        }

        return $text;
    }
}
