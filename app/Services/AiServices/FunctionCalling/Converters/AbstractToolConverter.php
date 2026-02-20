<?php

namespace App\Services\AiServices\FunctionCalling\Converters;

use App\Services\AiServices\FunctionCalling\ToolCall;
use Illuminate\Support\Collection;

abstract class AbstractToolConverter
{
    /**
     * Convert tool definitions to provider-specific format
     * @param Collection $tools
     * @return array Provider-formatted tools array
     */
    abstract public function convertToolDefinitions(Collection $tools): array;

    /**
     * Parse tool calls from provider response
     * @param array $response Raw API response
     * @return array<ToolCall> Parsed tool calls
     */
    abstract public function parseToolCalls(array $response): array;

    /**
     * Build tool results message for follow-up request
     * @param array<ToolCall> $toolCalls
     * @param array<\App\Services\AiServices\FunctionCalling\ToolResult> $results
     * @param array|null $rawResponse Original raw response (needed by some providers like OpenAI)
     * @return array Messages to append to conversation
     */
    abstract public function buildToolResultsMessage(array $toolCalls, array $results, ?array $rawResponse = null): array;

    /**
     * Check if response contains tool calls
     */
    abstract public function hasToolCalls(array $response): bool;

    /**
     * Check if response indicates completion (no more tool calls needed)
     */
    abstract public function isComplete(array $response): bool;

    /**
     * Extract text content from response (final answer)
     */
    abstract public function extractTextContent(array $response): string;

    /**
     * Extract generated images from API response (for built-in image generation tools)
     * Default implementation returns empty array - override in provider-specific converters
     *
     * @param array $response Raw API response
     * @return array Array of generated image data
     */
    public function extractGeneratedImages(array $response): array
    {
        return [];
    }

    /**
     * Check if response contains image generation results
     * Default implementation returns false - override in provider-specific converters
     *
     * @param array $response Raw API response
     * @return bool
     */
    public function hasImageGenerationResults(array $response): bool
    {
        return false;
    }

    /**
     * Extract code interpreter generated files from API response
     * Default implementation returns empty array - override in provider-specific converters
     *
     * @param array $response Raw API response
     * @return array Array of generated file data
     */
    public function extractCodeInterpreterFiles(array $response): array
    {
        return [];
    }

    /**
     * Check if response contains code interpreter results
     * Default implementation returns false - override in provider-specific converters
     *
     * @param array $response Raw API response
     * @return bool
     */
    public function hasCodeInterpreterResults(array $response): bool
    {
        return false;
    }

    /**
     * Extract all tool calls from response including built-in tools
     * This includes function calls, code_interpreter, image_generation, web_search, etc.
     * Used for displaying in reasoning steps UI
     * Default implementation returns empty array - override in provider-specific converters
     *
     * @param array $response Raw API response
     * @return array Array of tool call info with type and details
     */
    public function extractAllToolCalls(array $response): array
    {
        return [];
    }
}
